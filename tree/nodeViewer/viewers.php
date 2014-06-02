<?php

class nodeViewer_viewers extends nodeViewer {
	public $name = 'viewers';
	public $text = 'Affichages';
	public $needChildren = true;
	
	public function html($node){
		$ulId = uniqid('nw-viewers-');
		$children = array(
			"" => array(
				"nm" => "Résumé",
				"icon" => "file file-info"
			),
			"node" => array(
				"nm" => "Noeud",
				"icon" => "file file-edit"
			)/*,
			"edit" => array(
				"nm" => "Edition",
				"icon" => "file file-edit"
			)*/
		);
		if(isset($node['children']) && count($node['children']) > 0)
			$children["children"] = array(
				"nm" => "Descendants",
				"icon" => "file file-folder"
			);
		//var_dump($node['children']);
		$children["comment"] = array(
			"nm" => "Commentaires",
			"icon" => "file file-info"
		);
		
		if($node["typ"] == "query"){
			$children["query"] = array(
				"nm" => "Requête"
				, "icon" => "file file-query"
			); 
			$children["queryCall"] = array(
				"nm" => "Résultat"
				, "icon" => "file file-html"
				, "class" => "onclick-load"
			); 
		}
		else {
			$children["fileContent"] = array(
				"nm" => "Contenu"
				, "icon" => "file file-file"
			);
			$children["fileCall"] = array(
				"nm" => "Affichage"
				, "icon" => "file file-html"
				, "class" => "onclick-load"
			);
		} 
		
		$html = '<div class="header-footer ui-state-default ui-corner-all edq-viewers">'
			. $this->label($node, $this->path($node)) . ' #' . $node['id']
			. '<div class="toolbar">'
				. '<input type="checkbox" onchange="$(this).parents(\'.edq-viewers:first\').next().toggle();" checked="checked"/>'
			. '</div></div>';
		$html .= '<div class="edq-viewers" id="' . $ulId . '"><ul>';
		$nChild = 0;
		foreach($children as $id => $view){
			$href = $nChild == 0
				? '#' . $ulId . $id
				: 'tree/db.php?operation=get_view'
					. '&id=' . $node['id']
					. '&vw=' . $id
					. '&get=content'
			;
			//onclick-load
			if(isset($view["class"]))
				$class = ' class="' . $view["class"] . '"';
			else
				$class = '';
			$html .= '<li' . $class . '><a href="' . $href . '">'
				. $this->label($view)
				. '</a>'
				. '</li>';
			$nChild++;
		}
		$html .= '</ul>';
		//pré-chargement du 1er
		foreach($children as $id => $view){
			$html .= '<div id="' . $ulId . $id . '">';
			$viewer = nodeViewer::fromClass($id);
			$r = $viewer->html($node);
			$html .= $r["content"];
			$html .= '</div>';
			//first only
			break; 
		}
		$html .= '</div>';
		$html .= '<script>$().ready(function(){ $("#' . $ulId . '").tabs({
		beforeLoad: function( event, ui ) {
			if ( ui.tab.filter(":not(.onclick-load)").data( "loaded" ) ) {
				event.preventDefault();
				return;
			}
		
			ui.jqXHR.success(function(data) {
				ui.tab.filter(":not(.onclick-load)").data( "loaded", true );
			});
			
			ui.jqXHR.error(function(jqXHR, textStatus, errorThrown) {
				ui.panel.html("Impossible de charger le contenu.<br>" + errorThrown );
			});
		}
	});
 });
 </script>';
		return array(
			"title" => $node['nm']
			, "content" => $html
		);
	}
}

?>