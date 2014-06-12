<?php /* Gestion du contenu d'un fichier
UTF8 é
*/

require_once('file.php');
class nodeViewer_file_call extends nodeViewer_file {
	public $name = 'file.call';
	public $text = 'Affichage';
	
	public function html($node){
		global $tree;
		if(!isset($node["path"])){
			$node = $tree->get_node((int)$node['id'], array('with_path' => true, 'full' => false));
		}
		$file = $this->get_file($node);
		$exists = file_exists(utf8_decode($file));
		
		$href = $_SERVER["REQUEST_URI"];
		$head = '<div class="edq-toolbar">'
			. '<a class="edq-refresh" href="' . $href . '"'
			. ' onclick="var $parent = $(this).parents(\'.ui-widget-content:first\'); '
				. ' $.ajax(this.getAttribute(\'href\')).done(function(html){ $parent.html(html); }).fail(function(o,err){ alert(err) });'
				. ' return false;'
			. '">rafraîchir</a>'
			. '</div>';
			
		if($exists){
		
			ob_start();
			include(utf8_decode($file));
			$content = ob_get_clean();
			//var_dump($content);
		}
		else {
			$content = '<i>fichier absent</i>';
		}
			
		return array(
			"title" => $node['nm']
			, "content" => $head . $content
		);
	}
}

?>