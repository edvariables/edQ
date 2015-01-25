<?php

if(isset($_REQUEST['op'])
&& $_REQUEST['op'] == 'submit') {
	require_once(dirname(__FILE__) . '/param.operation.php');
	die();
}

class nodeViewer_legend extends nodeViewer {
	public $name = 'legend';
	public $text = 'Légende';
	public $needChildren = false;
	public $needPath = true;
	
	public function html($node, $options = false){
		
		Node::check_rights($node);
		
		$ulId = uniqid('nw-legend-');
		$children = array();
		$isDesign = is_design();
		
		// instance de node
		$node_obj = Node::fromClass($this->domain, $node);
		
		$toolbar = '<div class="header-footer ui-state-default ui-corner-all edq-viewers">'
			. $this->label($node, preg_replace('/^\/edQ\//', '', $this->path($node))) . ' #' . $node['id']
			. '<div class="toolbar">'
				. '<button class="ui-button ui-state-default ui-border-none" onclick="$(this).children(\':first\').toggleClass(\'ui-icon-triangle-1-n\').toggleClass(\'ui-icon-triangle-1-s\')'
					. '.parents(\'.header-footer:first\').next().toggle(function() { $(this).animate({ }, 200);}, function(){$(this).animate({}, 200);}); return false;">'
					. '<span class="ui-icon ui-icon-triangle-1-n" title="masque/affiche"> </span></button>'
				//. '<input type="checkbox" onchange="$(this).parents(\'.header-footer:first\').next().toggle();" checked="checked"/>'
				// lien qui déplace les noeuds du viewer dans un autre div + dialog
				//TODO mettre en plugin
				//TODO intégrer le header du contenu dans le header du dialog
				. '<button class="ui-button ui-state-default ui-border-none" onclick="var $tb = $(this).parents(\'.header-footer:first\');'
					. '$(\'<div></div>\').append($tb.parent().children())'
						.'.dialog({ title: $tb.children(\'label\').text(), width: \'auto\', height: \'auto\' })'
						.'.css(\'min-height\', \'20px\');
						$(this).remove(); return false;">'
					. '<span class="ui-icon ui-icon-newwin" title="affiche dans fen&ecirc;tre"> </span></button>'
			. '</div></div>';
		$html .= $toolbar ;
		
		return array(
			"title" => $node['nm']
			, "content" => $html
		);
	}
}

?>