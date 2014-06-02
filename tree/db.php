<?php
include_once(dirname(__FILE__) . '/../bin/session.php');
if(isset($_REQUEST['operation'])) {
	$dir = dirname(__FILE__);
	require_once($dir . '/../conf/db.conf.php');
	require_once($dir . '/../bin/class.db.php');
	require_once($dir . '/class.tree.php');
				
	$fs = new tree(db::get(DBTYPE . '://' . DBUSER . ':' . DBPASSWORD . '@' . DBSERVER . ':' . DBPORT . '/' . DBNAME)
		, array(
			'structure_table' => 'tree_struct'
			, 'data_table' => 'tree_data'
			, 'data' => array('nm', 'typ', 'icon')
			, 'full' => array('ext', 'params', 'ulvl', 'user')
		));
	global $tree;
	$tree = $fs;
	
	if(isset($_GET['operation'])) {
		$contentType = 'application/json';
		try {
			$rslt = null;
			switch($_GET['operation']) {
				case 'get_node':
					$node = isset($_GET['id']) && $_GET['id'] !== '#' ? (int)$_GET['id'] : 1;
					
					if($_GET['id'] === '#'
					&& isset($_SESSION['tree-root'])
					&& (is_numeric( $_SESSION['tree-root'] ))
					) {//racine
						$node = $_SESSION['tree-root'];
						$temp = array( $fs->get_node((int)$node, array('with_path' => false, 'full' => false)) );
					}
					else //enfants 
						$temp = $fs->get_children($node);
					
					$rslt = array();
					foreach($temp as $v) {
						if( $v['icon'] == null)
							$icon = $v['rgt'] - $v['lft'] > 1 ? "jstree-folder" : "jstree-file";
						else
							$icon = $v['icon'];
						$rslt[] = array('id' => $v['id'], 'text' => $v['nm'], 'icon' => $v['icon'], 'type' => $v['typ'], 'children' => ($v['rgt'] - $v['lft'] > 1));
					}
					break;
				case 'edit_node':
					$node = isset($_GET['id']) && $_GET['id'] !== '#' ? $_GET['id'] : 0;
					if(is_string($node))
						$node = explode(':', $node);
					if(count($node) > 1) {
						$rslt = array('content' => 'Multiple selected');
					}
					else {
						$node = $fs->get_node((int)$node[0], array('with_path' => false, 'full' => true));
						if( $node['icon'] == null)
							$icon = $node['rgt'] - $node['lft'] > 1 ? "jstree-folder" : "jstree-file";
						else
							$icon = $node['icon'];
						$rslt[] = array('id' => $node['id']
							, 'text' => $node['nm']
							, 'icon' => $node['icon']
							, 'type' => $node['typ']
							, 'ext' => $node['ext']
							, 'params' => $node['params']
							, 'user' => $node['user']
							, 'ulvl' => $node['ulvl']
							, 'children' => ($node['rgt'] - $node['lft'] > 1)
						);
					}
					break;
				case "get_content":
					$node = isset($_GET['id']) && $_GET['id'] !== '#' ? $_GET['id'] : 0;
					$node = explode(':', $node);
					if(count($node) > 1) {
						$rslt = array('content' => 'Multiple selected');
					}
					else {
						$temp = $fs->get_node((int)$node[0], array('with_path' => true, 'full' => true));
						$rslt = array(
							'title' => 'Selected: /' . implode('/',array_map(function ($v) { return $v['nm']; }, $temp['path'])). '/'.$temp['nm']
							, 'content' => '<pre style="line-height: 1em; text-align: left; padding-left: 1em;">'
								. print_r($temp, true) . '</pre>'
						);
					}
					break;
				case "get_view":
					require_once(dirname(__FILE__) . '/nodeViewer/_class.php');
				
					$vw = isset($_GET['vw']) && $_GET['vw'] !== null ? $_GET['vw'] : '';
					$viewer = nodeViewer::fromClass($vw);
					
					$node = isset($_GET['id']) && $_GET['id'] !== '#' ? $_GET['id'] : 0;
					$node = explode(':', $node);	

					if(count($node) > 1) {
						$rslt = array('content' => 'Multiple selected');
					}
					else {
						$temp = $fs->get_node((int)$node[0], array('with_path' => true, 'with_children' => $viewer->needChildren, 'full' => true));
						try {
							$rslt = $viewer->html($temp);
						}
						catch(Exception $ex){
							$rslt = array(
								"error" => $ex
								, "content" => '<div class="edq-error">' . $ex . '</div>'
							);
						}
					}
						
					if(isset($_REQUEST["get"]) && $_REQUEST["get"] != ''){
						$contentType = 'application/html';	
						if(is_array($rslt))
							$rslt = $rslt[$_REQUEST["get"]];
						else {
							$contentType = 'application/html';		
							$rslt = '<div class="edq-error">' . $rslt . '</div>';
						}
					}
					break;
				case 'create_node':
					$node = isset($_GET['id']) && $_GET['id'] !== '#' ? (int)$_GET['id'] : 0;
					$temp = $fs->mk($node, isset($_GET['position']) ? (int)$_GET['position'] : 0, array('nm' => isset($_GET['text']) ? $_GET['text'] : 'New node'));
					$rslt = array('id' => $temp);
					break;
				case 'rename_node':
					$node = isset($_GET['id']) && $_GET['id'] !== '#' ? (int)$_GET['id'] : 0;
					$rslt = $fs->rn($node, array('nm' => isset($_GET['text']) ? $_GET['text'] : 'Renamed node'));
					break;
				case 'update_node':
					$node = isset($_GET['id']) && $_GET['id'] !== '#' ? (int)$_GET['id'] : 0;
					$params = array();
					if(isset($_REQUEST['text']))
						$params["nm"] = $_REQUEST['text'];
					if(isset($_REQUEST['type']))
						$params["typ"] = $_REQUEST['type'];
					if(isset($_REQUEST['icon']))
						$params["icon"] = $_REQUEST['icon'];
					if(isset($_REQUEST['ext']))
						$params["ext"] = $_REQUEST['ext'];
					if(isset($_REQUEST['params']))
						$params["params"] = $_REQUEST['params'];
					if(isset($_REQUEST['ulvl']))
						$params["ulvl"] = $_REQUEST['ulvl'];
					if(isset($_REQUEST['user']))
						$params["user"] = $_REQUEST['user'];
					$rslt = $fs->rn($node, $params, true);
					break;
				case 'delete_node':
					$node = isset($_GET['id']) && $_GET['id'] !== '#' ? (int)$_GET['id'] : 0;
					$rslt = $fs->rm($node);
					break;
				case 'move_node':
					$node = isset($_GET['id']) && $_GET['id'] !== '#' ? (int)$_GET['id'] : 0;
					$parn = isset($_GET['parent']) && $_GET['parent'] !== '#' ? (int)$_GET['parent'] : 0;
					$rslt = $fs->mv($node, $parn, isset($_GET['position']) ? (int)$_GET['position'] : 0);
					break;
				case 'copy_node':
					$node = isset($_GET['id']) && $_GET['id'] !== '#' ? (int)$_GET['id'] : 0;
					$parn = isset($_GET['parent']) && $_GET['parent'] !== '#' ? (int)$_GET['parent'] : 0;
					$rslt = $fs->cp($node, $parn, isset($_GET['position']) ? (int)$_GET['position'] : 0);
					break;
				default:
					throw new Exception('Unsupported operation: ' . $_GET['operation']);
					break;
			}
			header('Content-Type: ' . $contentType . '; charset=utf8');
			if($contentType == 'application/json')	
				echo json_encode($rslt);
			else
				echo ($rslt);
		}
		catch (Exception $e) {
			header($_SERVER["SERVER_PROTOCOL"] . ' 500 Server Error');
			header('Status:  500 Server Error');
			echo $e->getMessage();
		}
		die();
	}
}
?>