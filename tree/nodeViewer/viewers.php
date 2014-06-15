<?php

class nodeViewer_viewers extends nodeViewer {
	public $name = 'viewers';
	public $text = 'Affichages';
	public $needChildren = true;
	
	public function html($node){
		$ulId = uniqid('nw-viewers-');
		$children = array();
		$isDesign = isDesign();
		if($isDesign){
			$children[''] = array(
				"nm" => "Résumé",
				"icon" => "file file-info"
			);
			$children['node'] = array(
				"nm" => "Noeud",
				"icon" => "file file-edit"
			);/*,
			$children['edit'] = array(
				"nm" => "Edition",
				"icon" => "file file-edit"
			);*/
		}
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
			if($isDesign)
				$children["query"] = array(
					"nm" => "Requête"
					, "icon" => "file file-query"
				); 
			$children["query.call"] = array(
				"nm" => "Résultat"
				, "icon" => "file file-html"
				, "class" => "onclick-load"
			); 
		}
		else if($node["typ"] == "jqGrid"){
			if($isDesign)
				$children["jqGrid"] = array(
					"nm" => "Requête"
					, "icon" => "file file-query"
				); 
			$children["jqGrid.call"] = array(
				"nm" => "Résultat"
				, "icon" => "file file-html"
				, "class" => "onclick-load"
			); 
		}
		else {
			if($isDesign)
				$children["file.content"] = array(
					"nm" => "Contenu"
					, "icon" => "file file-file"
				);
			$children["file.call"] = array(
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
		$defaultView = null;
		$defaultViewIndex = 0;
		// detection de l'onglet par défaut
		foreach($children as $id => $view){
			//if($isDesign){
				if($defaultView === null){
					$defaultView = $id; //1er
					$defaultViewIndex = $nChild;
				}
			//}
			//else {
			//	$defaultView = $id; //dernier
			//	$defaultViewIndex = $nChild;
			//}
			$nChild++;
		}
		// onglets
		foreach($children as $id => $view){
			$href = $defaultView == $id
				? '#' . $ulId . $id
				: 'view.php'
					. '?id=' . $node['id']
					. '&vw=' . $id
					. ($isDesign ? '&design=1' : '')
			;
			
			$class = ' class="edq-viewer-' . $id;
			if(isset($view["class"]))
				$class .= ' ' . $view["class"]; //onclick-load
			$class = str_replace('.', '-', strtolower( $class ) )
				. '"';

			$html .= '<li' . $class . '><a href="' . $href . '">'
				. $this->label($view)
				. '</a>'
				. '</li>';
		}
		$html .= '</ul>';
		
		// panels
		//pré-chargement du 'par défaut'
		//foreach($children as $id => $view){
		$id = $defaultView;
		$view = $children[$id];
			$class = ' class="edq-viewer-' . $id;
			if(isset($view["class"]))
				$class .= ' ' . $view["class"]; //onclick-load
			$class = strtolower( $class . '"' );
			$html .= '<div id="' . $ulId . $id . '"' . $class . '>';
			$viewer = nodeViewer::fromClass($id);
			$r = $viewer->html($node);
			$html .= $r["content"];
			$html .= '</div>';
			//first only
		//	break; 
		//}
		$html .= '</div>';
		$html .= '<script>$().ready(function(){ $("#' . $ulId . '").tabs({
		active: ' . $defaultViewIndex . ',
		beforeLoad: function( event, ui ) {
			if ( ui.tab.filter(":not(.onclick-load)").data( "loaded" ) ) {
				event.preventDefault();
				return;
			}
		
			ui.jqXHR.success(function(data) {
				var className = ui.tab.attr("class").replace(/^.*(\\bedq-viewer-[^\\s]*)(\\s.*)?$/, "$1");
				ui.panel.addClass(className);
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