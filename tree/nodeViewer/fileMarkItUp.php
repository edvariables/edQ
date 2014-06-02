<?php /* Gestion du contenu d'un fichier
UTF8 é
*/
if(isset($_POST['operation'])
&& $_POST['operation'] == 'submit') {
	require_once('_class.php');
	$file = $_SERVER['DOCUMENT_ROOT']
			. $_POST['fl'];
	$parent = dirname($file);
	if(!file_exists(utf8_decode($parent)))
		mkdir(utf8_decode($parent), 0777, true);
			
	file_put_contents(utf8_decode($file), $_POST['value']);
	
	die("1");
}
require_once('file.php');
class nodeViewer_fileMarkItUp extends nodeViewer_file {
	public $name = 'fileMarkItUp';
	public $text = 'Fichier';
	
	public function html($node){
		global $tree;
		if(!isset($node["path"])){
			$node = $tree->get_node((int)$node['id'], array('with_path' => true, 'full' => false));
		}
		$file = $this->get_file($node);
		$exists = file_exists(utf8_decode($file));
		if($exists){
			$content = file_get_contents(utf8_decode($file));
		}
		else
			$content = '';
		
		$uid = uniqid('form-');
			
		$head = '
	<!-- markItUp! skin -->
	<link rel="stylesheet" type="text/css" href="markitup/skins/simple/style.css">
	<!--  markItUp! toolbar skin -->
	<link rel="stylesheet" type="text/css" href="markitup/sets/html/style.css">
	<script> if(!$.fn.markItUp){
			$.getScript("markitup/jquery.markitup.js");
	}</script>'
	/*
	<!-- markItUp! -->
	<script type="text/javascript" src="markitup/jquery.markitup.js"></script>
	*/
	. '<!-- markItUp! toolbar settings -->
	<script type="text/javascript" src="markitup/sets/html/set.js"></script>
';
		$script = '<script type="text/javascript">
$(function() {
	// Add markItUp! to your textarea in one line
	var OptionalExtraSettings = {
		previewParserPath: "~/preview.php"
	};
	$("#' . $uid . ' textarea:first").markItUp(mySettings, OptionalExtraSettings)
		.parents(".markItUp:first").css("width", "99%");


});
</script>
		';
		
		return array(
			"title" => $node['nm']
			, "content" => $head
				. '<form id="' . $uid . '" method="post" action="' . $this->get_url() . '">'
				. '<legend>' . $file
					. ($exists ? '' : ' <small><i>(n\'existe pas)</i></small>')
				. '</legend>'
				. '<input type="hidden" name="id" value="' . $node['id'] . '"/>'
				. '<input type="hidden" name="vw" value="' . __CLASS__ . '"/>'
				. '<input type="hidden" name="fl" value="' . substr( $file, strlen( $_SERVER['DOCUMENT_ROOT'] ) ) . '"/>'
				. '<input type="hidden" name="operation" value="submit"/>'
				. '<fieldset>'
				. '<textarea name="value" style="width:100%;" rows="12">' . htmlspecialchars($content) . '</textarea>'
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