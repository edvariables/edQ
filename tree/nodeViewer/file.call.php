<?php /* Gestion du contenu d'un fichier
UTF8 é
*/

require_once('file.php');
class nodeViewer_file_call extends nodeViewer_file {
	public $name = 'file.call';
	public $text = 'Affichage';
	
	public function html($node, $options = false){
		global $tree;
		if(!isset($node["path"])){
			$node = $tree->get_node((int)$node['id'], array('with_path' => true, 'full' => false));
		}
		$file = $this->get_file($node);
		$exists = file_exists($file);
		if(!$exists){
			$file = utf8_decode($file);
			$exists = file_exists($file);
		}
		
		$href = page::url($node);//$_SERVER["REQUEST_URI"];		
		
		/* legend
		 * demandée par /page.php pour affichage seul
		 */
		if(is_array($options) && isset($options['vw--legend'])){
			$legend = $options['vw--legend'];
		}
		elseif (isset($_REQUEST['vw--legend'])){
			$legend = $_REQUEST['vw--legend'];
			unset($_REQUEST['vw--legend']);
		}
		else
			$legend = false;
		if($legend){
			include_once(dirname(__FILE__) . '/legend.php');
			$legend = new nodeViewer_legend();
			$legend = $legend->html($node, $options);
			$head = $legend['content'];
		} else $head = '';
		
		/* toolbar */
		if(is_array($options) && isset($options['vw--toolbar'])){
			$toolbar = $options['vw--toolbar'];
		}
		elseif (isset($_REQUEST['vw--toolbar'])){
			$toolbar = $_REQUEST['vw--toolbar'];
			unset($_REQUEST['vw--toolbar']);
		}
		else
			$toolbar = false;
		if($toolbar){
			$head .= '<div class="edq-toolbar">'
				. '<a class="edq-refresh" href="' . $href . '&vw--toolbar=1"'
				. ' onclick="var $parent = $(this).parents(\'.ui-widget-content:first\'); '
					. ' $.ajax(this.getAttribute(\'href\')).done(function(html){ $parent.html(html); }).fail(function(o,err){ alert(err) });'
					. ' return false;'
				. '">rafraîchir</a>'
				. '</div>';
		}
		
		if($exists){
			$view = $this;
			ob_start();
			include($file);
			$content = ob_get_clean();
			//var_dump($content);
		}
		else {
			$content = '<i class="edq-error">fichier absent</i>';
		}
			
		return array(
			"title" => $node['nm']
			, "content" => $head . $content
		);
	}
}

?>