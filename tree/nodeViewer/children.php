<?php

class nodeViewer_children extends nodeViewer {
	public $name = 'children';
	public $text = 'Descendants';
	public $needChildren = true;
	
	public function html($node){
		$ulId = uniqid('nw-children-');
		$html = '<div class="edq-nodes" id="' . $ulId . '"><ul>';
		global $tree;
		$children = $tree->get_children($node['id']);
		$nChild = 0;
		$design = isDesign();
		foreach($children as $child)
			if($design || !$child['design']){
				$href = $nChild == 0
					? '#' . $ulId . $child['id']
					: 'tree/db.php?operation=get_view'
						. '&id=' . $child['id']
						. '&vw=' . 'viewers'
						. '&get=content'
						. ($design ? '&design=1' : '')
				;
				$html .= '<li>'
					. '<a href="' . $href . '">'
					. $this->label($child)
					. '</a>'
					. '</li>';
				$nChild++;
			}
		$html .= '</ul>';
		//panneaux

		$viewer = nodeViewer::fromClass('viewers');
		foreach($children as $child)
			if($design || !$child['design']){
			
				$child = $tree->get_node($child['id'], array('with_path' => false, 'with_children' => true, 'full' => true));
				
				$html .= '<div id="' . $ulId . $child['id'] . '">';
				
				$r = $viewer->html($child);
				$html .= $r["content"];
				$html .= '</div>';
				//first only
				break;
			}
		$html .= '</div>';
		$html .= '<script>$().ready(function(){ $("#' . $ulId . '").tabs(); } );</script>';
		return array(
			"title" => $node['nm']
			, "content" => $html
		);
	}
}

?>