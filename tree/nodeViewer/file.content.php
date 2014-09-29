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
			$type = "application/x-httpd-php";
			break;
		case "php" :
			$type = "application/x-httpd-php";
			break;
		case "folder" :
			$type = "application/x-httpd-php";
			break;
		case "css" :
			$type = "text/x-scss";
			break;
		default:
			$type = "application/x-httpd-php";
			break;
		}
		$uid = uniqid('form-');
			
		$head = '';
		$script = '<script type="text/javascript">
$().ready(function() {
	var $textarea = $("#' . $uid . '-input");
	var editor = CodeMirror.fromTextArea($textarea.get(0), {
        lineNumbers: true,
        matchBrackets: true,
        mode: "' . $type . '",
        indentUnit: 4,
	autofocus: true,
        indentWithTabs: true,
		extraKeys: {
			"Ctrl-S": function(instance) { $("#' . $uid . '").submit(); },
			"Cmd-S": function(instance) { $("#' . $uid . '").submit(); }
		  }
	});
	$textarea.nextAll(".CodeMirror:first").resizable({
		resize: function() {
			editor.setSize($(this).width(), $(this).height());
			editor.refresh();
		}
	});
	$textarea.data("editor", editor);
});
</script>
		';
		$beforeSubmit = '
			var $textarea = $("#' . $uid . '-input");
			$textarea.data("editor").save();
		';
		
		$toolbar = '
			<div class="edq-toolbar">
			<input type="submit" value="Enregistrer"/>
			<div style="float: right; opacity: 0.6;">
				<span title="ui-icon-arrowthick-1-n" class="ui-icon ui-icon-arrowthick-1-n"
					onclick="var $textarea = $(this).parents(\'form:first\').find(\'textarea:first\');
						var editor = CodeMirror.fromTextArea($textarea.get(0));
						var $codeMirror = $textarea.nextAll(\'.CodeMirror:first\');
						editor.setSize($codeMirror.width(), $codeMirror.height()*0.7);
						editor.refresh();
						">&nbsp;</span>
				<span title="ui-icon-arrowthick-1-s" class="ui-icon ui-icon-arrowthick-1-s"
					onclick="var $textarea = $(this).parents(\'form:first\').find(\'textarea:first\');
						var editor = CodeMirror.fromTextArea($textarea.get(0));
						var $codeMirror = $textarea.nextAll(\'.CodeMirror:first\');
						//editor.setSize($codeMirror.width(), $codeMirror.height()*1.7);
						$codeMirror.css(\'height\', $codeMirror.height() * 1.4);
						editor.refresh();
						">&nbsp;</span>
			</div>
			</div>
		';
		return array(
			"title" => $node->label()
			, "content" => $head
				. '<form id="' . $uid . '" method="post" action="' . $this->get_url($node) . '">'
				. '<input type="hidden" name="id" value="' . $node->id . '"/>'
				. '<input type="hidden" name="vw" value="' . __CLASS__ . '"/>'
				. '<input type="hidden" name="fl" value="' . substr( $file, strlen( $_SERVER['DOCUMENT_ROOT'] ) ) . '"/>'
				. '<input type="hidden" name="op" value="submit"/>'
				. '<fieldset class="ui-widget-content ui-corner-all">'
				. '<legend class="ui-state-default ui-corner-all"><code>' . $file
					. ($exists ? ' <small>(' . date('d/m/Y H:i:s', filemtime($file)) . ')</small>' : '') . '</code>'
					. ($exists ? '' : ' <small><i>(n\'existe pas)</i></small>')
				. '</legend>'
				. '<textarea id="' . $uid . '-input" name="value" style="width:100%;" rows="12" spellcheck="false">'
					. htmlspecialchars($content)
				. '</textarea>'
				. '</fieldset>'
				. '<fieldset class="ui-widget-content ui-corner-all">'
				. $toolbar
				. '</fieldset>'
				. '</form>'
				. $this->formScript($uid, null, $beforeSubmit)
				. $script
		);
	}
}

?>