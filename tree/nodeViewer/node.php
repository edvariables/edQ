<?php /* Gestion des propriétés d'un noeud
UTF8 é
*/
if(isset($_POST['operation'])
&& $_POST['operation'] == 'submit') {

	require_once(dirname(__FILE__) . '/../db.php');	 //TODO load tree only
	global $tree;
	
	$node = isset($_REQUEST['id']) && $_REQUEST['id'] !== '#' ? (int)$_REQUEST['id'] : 0;
	$params = array();
	if(isset($_REQUEST['name']))
		$params["nm"] = $_REQUEST['name'];
	if(isset($_REQUEST['icon']))
		$params["icon"] = $_REQUEST['icon'];
	if(isset($_REQUEST['type']))
		$params["typ"] = $_REQUEST['type'];
	if(isset($_REQUEST['ulvl']))
		$params["ulvl"] = $_REQUEST['ulvl'];
	if(isset($_REQUEST['ext']))
		$params["ext"] = $_REQUEST['ext'];
	if(isset($_REQUEST['params']))
		$params["params"] = $_REQUEST['params'];
	if(isset($_REQUEST['ulvl']))
		$params["ulvl"] = $_REQUEST['ulvl'];
	if(isset($_REQUEST['user']))
		$params["user"] = $_REQUEST['user'];
	$rslt = $fs->rn($node, $params, true);
	die("1");
}

require_once(dirname(__FILE__) . '/../helpers.php');
	
class nodeViewer_node extends nodeViewer {
	public $name = 'node';
	public $text = 'Noeud';
	
	public function html($node){
		if(!isset($node["typ"])){
			global $tree;
			$node = $tree->get_node((int)$node['id'], array('with_path' => true, 'full' => true));
		}
		
		// instance de node
		$node = node::fromClass($this->domain, $node);
		
		// Type
		$types = array("folder"
			, "html"
			, "css"
			, "php"
			, "js"
			, "url"
			, "dataSource"
			, "sql"
			, "query"
		);
		$select_type = '<select name="type" value="' . ifNull($node->type, '') . '">';
		foreach($types as $type)
			$select_type .= '<option value="' . $type . '"'
				. ($node->type == $type ? ' selected="selected"' : '')
			 	. '>' . htmlentities($type) . '</option>';
		$select_type .= '</select>';

		// icon
		$icons = array("file file-file"
			, "file file-folder"
			, "file file-folder-sys"
			, "file file-sql"
			, "file file-cs"
			, "file file-css"
			, "file file-htm"
			, "file file-php"
			, "file file-c"
			, "file file-iso"
			, "file file-js"
			, "file file-pdf"
			, "file file-query"
		);
		$select_icon = '<select name="icon" value="' . ifNull($node->icon, '') . '">';
		foreach($icons as $icon)
			$select_icon .= '<option value="' . $icon . '"'
					. ($node->icon == $icon ? ' selected="selected"' : '')
			 	. '>'
					. '<i class="jstree-icon ' . $icon . '"></i>'
					. preg_replace('/^(jstree|file\sfile)-/', '', $icon)
				. '</option>';
		$select_icon .= '</select>';
		
		// ulvl
		$ulvls = array(
			"0" => "Administrateur"
			, "1" => "Gestionnaire"
			, "2" => "Utilisateur"
			, "999" => "Public"
		);
		$select_ulvl = '<select name="ulvl" value="' . ifNull($node->ulvl, '') . '">';
		foreach($ulvls as $ulvl => $text)
			$select_ulvl .= '<option value="' . $ulvl . '"'
					. ($node->ulvl == $ulvl ? ' selected="selected"' : '')
			 	. '>'
					. htmlentities($text)
				. '</option>';
		$select_ulvl .= '</select>';
		
		// Form
		$uid = uniqid('form-');
		$html = '<form id="' . $uid . '" method="post" action="' . $this->get_url($node) . '">'
			. '<fieldset class="q-fields">'
			. '<input type="hidden" name="id" value="' . $node->id . '"/>'
			. '<input type="hidden" name="vw" value="' . __CLASS__ . '"/>'
			. '<input type="hidden" name="operation" value="submit"/>'
			. '<div>'
			. '<div><label class="ui-state-default ui-corner-all">Nom</label>'
				. '<input size="40" name="name" value="' . $node->name . '"/></div>'
			. '<div><label class="ui-state-default ui-corner-all">Icône</label>'
				. $select_icon . '</div>'
			. '<div><label class="ui-state-default ui-corner-all">Type</label>'
				. $select_type . '</div>'
			. '<div><label class="ui-state-default ui-corner-all">Clé externe</label>'
				. '<input size="40" name="ext" value="' . ifNull($node->ext, '') . '"/></div>'
			. '<div><label class="ui-state-default ui-corner-all">Paramètres</label>'
				. '<textarea rows="3" name="params" style="width: 90%">' . ifNull($node->params, '') . '</textarea></div>'
			. '<div><label class="ui-state-default ui-corner-all">Sécurité</label>'
				. $select_ulvl . '</div>'
			. '<div><label class="ui-state-default ui-corner-all">Propriétaire</label>'
				. '<input size="40" name="user" value="' . ifNull($node->user, '') . '"/></div>'
			. '</div></fieldset>'
					. '<fieldset>'
					. '<input type="submit" value="Enregistrer"/>'
					. '</fieldset>'
				. '</form>'
				. $this->formScript($uid, null, '
					var value = $("#' . $uid . '").find(\':input[name="name"]\').val();
					$("#tree").jstree("reload_node", ' . $node->id . ');
					//$("#tree").jstree("set_text", ' . $node->id . ', value, false);
				')
		;
		return array(
			"title" => $node->name
			, "content" => $html
		);
	}
}

?>