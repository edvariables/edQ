<?php /* Gestion d'un fichier
Destiné à être hérité par fileCall, fileContent
*/
class nodeViewer_file extends nodeViewer {
	public $name = 'file';
	public $text = 'Fichier';
	
	/* get_file
	*/
	public function get_file($node){
		if(is_array($node))
			return $this->get_page_path($node) . '/' .$node['nm'] . '.php';
		return $node->get_page_path() . '/' .$node->name . '.php';
	}
	
	/* html
		overridable
	*/
	public function html($node){
		global $tree;
		if(!isset($node["path"])){
			$node = $tree->get_node((int)$node['id'], array('with_path' => true, 'full' => false));
		}
		$file = $this->get_file($node);
		$exists = file_exists($file) || file_exists(utf8_decode($file));
		if($exists){
			$content = '<h3>' . $file . '</h3>';
		}
		else
			$content = '<i>fichier absent</i>';
		
			
		return array(
			"title" => $node['nm']
			, "content" => $content
		);
	}
}

?>