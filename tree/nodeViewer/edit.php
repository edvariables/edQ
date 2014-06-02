<?php

require_once(dirname(__FILE__) . '/../helpers.php');
	
class nodeViewer_edit extends nodeViewer {
	public $name = 'edit';
	public $text = 'Edition';
	
	public function html($node){
		if(!isset($node["typ"])){
			global $tree;
			$node = $tree->get_node((int)$node['id'], array('with_path' => true, 'full' => true));
		}
		$html = '<form action="?operation=update_node&id=' . $node['id'] . '">'
			. '<fieldset class="q-fields"><div>'
			. '<div><label class="ui-state-default ui-corner-all">Nom</label>'
				. '<input size="40" value="' . $node["nm"] . '"/></div>'
			. '<div><label class="ui-state-default ui-corner-all">Icône</label>'
				. '<input size="40" value="' . ifNull($node["icon"], '') . '"/></div>'
			. '<div><label class="ui-state-default ui-corner-all">Type</label>'
				. '<input size="40" value="' . ifNull($node["typ"], '') . '"/></div>'
			. '<div><label class="ui-state-default ui-corner-all">Clé externe</label>'
				. '<input size="40" value="' . ifNull($node["ext"], '') . '"/></div>'
			. '<div><label class="ui-state-default ui-corner-all">Paramètres</label>'
				. '<textarea rows="3" style="width: 90%">' . ifNull($node["params"], '') . '</textarea></div>'
			. '</div></fieldset>'
			. '<fieldset>'
			. '<button >Enregistrer</button>'
			. '</fieldset>'
			. '</form>'
		;
		return array(
			"title" => $node['nm']
			, "content" => $html
		);
	}
}

?>