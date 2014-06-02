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
	
	$content = $_POST['value'];
	//unescape
	$content = str_replace('<div class="php_code">&lt;?php', '<'.'?php', $content);
	$content = str_replace('<div class="php_code">&lt;?=', '<'.'?=', $content);
	$content = str_replace("?&gt;</div>", "?".">", $content);
	//save
	file_put_contents(utf8_decode($file), $content);
	
	die("1");
}
require_once('file.php');
class nodeViewer_fileTinyMCE extends nodeViewer_file {
	public $name = 'fileTinyMCE';
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
			//escape
			/*$content = str_replace('<'.'?php', '<div class="php_code">&lt;?php', $content);
			$content = str_replace('<'.'?=', '<div class="php_code">&lt;?=', $content);
			$content = str_replace("?".">", "?&gt;</div>", $content);*/
		}
		else
			$content = '';
		
		$uid = uniqid('form-');
			
		$head = '
	<script> if(!window["tinyMCE"]){
		$.ajaxSetup({async:false});
		$.getScript("tinymce/tinymce.min.js");
		
    	$.extend(tinyMCE, {
			baseURL : "tinymce",
			documentBaseURL : "tinymce"
		});
	}
	tinyMCEOptions = {
		mode : "textareas",
		/*plugins : "inlinepopups",*/
		setup : function(ed) {
			ed.on("BeforeSetContent", function(e) { 
			   //replace all instances of < ?php and ? > with HTML entities
				e.content = e.content
					.replace(/<\?(php|=)/gi, \'<em class="php_tag open">&lt?$1</em>\')
					.replace(/\?>/gi, \'<em class="php_tag close">?&gt</em>\')
				;
			});
		 },
		 content_css : "edq.css"
		 
	};
	</script>
';
		//script d'initilisation
		$script = '<script type="text/javascript">
$().ready(function() {
	tinyMCE.init(tinyMCEOptions);
	tinyMCE.execCommand("mceAddControl", true, "' . $uid . '-value")
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
				. '<textarea id="' . $uid . '-value" name="value" style="width:100%;" rows="12">' . htmlspecialchars($content) . '</textarea>'
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