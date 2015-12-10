<?php /* Gestion du contenu d'un fichier
UTF8 é
*/
if(isset($_POST['op'])
&& $_POST['op'] == 'submit') {
	require_once('_class.php');
	$file = $_SERVER['DOCUMENT_ROOT'];
	if($file[strlen($file)-1] != '/'
	&& $_POST['fl'][0] != '/')
		$file .= '/' . $_POST['fl'];
	else
		$file .= $_POST['fl'];
	$parent = dirname($file);
	if(!file_exists(utf8_decode($parent))){
		//var_dump(realpath(utf8_decode($parent)));
		mkdir((utf8_decode($parent)), 0777, true);
	}
			
	file_put_contents(utf8_decode($file), $_POST['value']);
	
	die("1");
}
require_once('file.php');
class nodeViewer_file_content extends nodeViewer_file {
	public $name = 'file.content';
	public $text = 'Fichier';
	
	public function html($node, $options = false){
		global $tree;
		if(!isset($node["path"])){
			$node = $tree->get_node((int)$node['id'], array('with_path' => true, 'full' => false));
		}
		// instance de node
		$node = Node::fromClass($this->domain, $node);
		
		$file = $this->get_file($node);
		$exists = file_exists(utf8_decode($file));
		if($exists){
			$content = file_get_contents(utf8_decode($file));
		}
		else
			$content = '';
		
		$type = $node->type;
		switch($type){
		case null :
			$type = "html";
			break;
		case "php" :
			$type = "html";
			break;
		case "default" :
			$type = "html";
			break;
		case "folder" :
			$type = "html";
			break;
		default:
			break;
		}
		$uid = uniqid('form-');
			
		$head = '
	<!-- markItUp! skin -->
	<link rel="stylesheet" type="text/css" href="jquery/markitup/skins/simple/style.css">
	<!--  markItUp! toolbar skin -->
	<link rel="stylesheet" type="text/css" href="jquery/markitup/sets/'. $type .'/style.css">
	<script> if(!$.fn.markItUp){
			$.getScript("jquery/markitup/jquery.markitup.js");
	}</script>'
	/*
	<!-- markItUp! -->
	<script type="text/javascript" src="jquery/markitup/jquery.markitup.js"></script>
	*/
	. '<!-- markItUp! toolbar settings -->
	'. ($type != "dataSource"
		? '<script type="text/javascript" src="jquery/markitup/sets/'. $type .'/set.js"></script>'
		: '<script>mySettings = mySettings_' . $type . '; </script>') . '
';
		$script = '<script type="text/javascript">
$().ready(function() {
	// Add markItUp! to your textarea in one line
	var OptionalExtraSettings = {
		previewParserPath: "~/preview.php"
	};
	$("#' . $uid . ' textarea:first").markItUp(mySettings, OptionalExtraSettings)
		.parents(".markItUp:first")
			.css("width", "99%")
			.addClass("type-' . $type . '");


});
</script>
		';
		
		return array(
			"title" => $node->label()
			, "content" => $head
				. '<form id="' . $uid . '" method="post" action="' . $this->get_url($node) . '">'
				. '<input type="hidden" name="id" value="' . $node->id . '"/>'
				. '<input type="hidden" name="vw" value="' . __CLASS__ . '"/>'
				. '<input type="hidden" name="fl" value="' . substr( $file, strlen( $_SERVER['DOCUMENT_ROOT'] ) ) . '"/>'
				. '<input type="hidden" name="op" value="submit"/>'
				. '<fieldset>'
				. '<legend><code>' . $file . '</code>'
					. ($exists ? '' : ' <small><i>(n\'existe pas)</i></small>')
				. '</legend>'
				. '<textarea name="value" style="width:100%;" rows="12" spellcheck="false">'
					. htmlspecialchars($content)
				. '</textarea>'
				. '</fieldset>'
				. '<fieldset>'
				. '<input type="submit" value="Enregistrer"/>'
				. '</fieldset>'
				. '</form>'
				. $this->formScript($uid)
				. $script
		);
	}
}

?>