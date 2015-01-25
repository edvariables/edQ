<?php

if(isset($_REQUEST['op'])
&& $_REQUEST['op'] == 'submit') {
	require_once(dirname(__FILE__) . '/param.operation.php');
	die();
}

include_once(dirname(__FILE__) . '/legend.php');
	
class nodeViewer_viewers extends nodeViewer {
	public $name = 'viewers';
	public $text = 'Affichages';
	public $needChildren = true;
	
	public function html($node, $options = false){
				
		$ulId = uniqid('nw-viewers-');
		$children = array();
		$isDesign = is_design();
		
		// instance de node
		$node_obj = Node::fromClass($this->domain, $node);
		$preferSort = $node_obj->param_value('sort', false, 'viewers');
		if($preferSort)
			$preferSort = json_decode($preferSort, true);
			
		if($isDesign){
			$children[''] = array(
				"nm" => "Résumé",
				"icon" => "file file-info"
			);
			$children['node'] = array(
				"nm" => "Noeud",
				"icon" => "file file-edit"
			);
		}
		$children["comment"] = array(
			"nm" => "Commentaires",
			"icon" => "file file-info"
		);
		if(isset($node['children']) && count($node['children']) > 0)
			$children["children"] = array(
				"nm" => "Descendants",
				"icon" => "file file-folder"
			);
		//var_dump($node['children']);
		
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
					, "icon" => "file file-html"
				);
			$children["file.call"] = array(
				"nm" => "Affichage"
				, "icon" => "file file-iso"
				, "class" => "onclick-load"
			);
		} 
		if(is_array($preferSort)){
			//var_dump($preferSort);
			$index = 1000;
			foreach($children as $type => $child)
				$children[$type]["sortIndex"] = $index++;
			//var_dump($children);
			$index = 0;
			foreach($preferSort as $type){
				if(isset($children[str_replace('-', '.', $type)]))
					$children[str_replace('-', '.', $type)]["sortIndex"] = $index++;
			}
			helpers::aasort($children,"sortIndex");
			//var_dump($children);
			
		}
		
		$legend = new nodeViewer_legend();
		$toolbar = $legend->html($node, $options);
		$html .= $toolbar['content'] . '<div class="edq-viewers" id="' . $ulId . '"><ul>';
		//$html = '<div class="edq-viewers" id="' . $ulId . '">'.$toolbar.'<ul>';
		$nChild = 0;
		$defaultView = null;
		$defaultViewIndex = 0;
		// detection de l'onglet par défaut
		foreach($children as $type => $view){
			//if($isDesign){
				if($defaultView === null){
					$defaultView = $type; //1er
					$defaultViewIndex = $nChild;
				}
			//}
			//else {
			//	$defaultView = $type; //dernier
			//	$defaultViewIndex = $nChild;
			//}
			$nChild++;
		}
		// onglets
		foreach($children as $type => $view){
			$href = $defaultView == $type
				? '#' . $ulId . $type
				: 'view.php'
					. '?id=' . $node['id']
					. '&vw=' . $type
					. ($isDesign ? '&design=1' : '')
					. '&vw--toolbar=1'
			;
			
			$class = ' class="edq-viewer-' . str_replace('.', '-', $type);
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
		$type = $defaultView;
		$view = $children[$type];
			$class = ' class="edq-viewer-' . str_replace('.', '-', $type);
			if(isset($view["class"]))
				$class .= ' ' . $view["class"]; //onclick-load
			$class = strtolower( $class . '"' );
			$html .= '<div id="' . $ulId . $type . '"' . $class . '>';
			try {
				$viewer = nodeViewer::fromClass($type);
				$r = $viewer->html($node, array('vw--toolbar' => true));
				$html .= $r["content"];
			}
			catch(Exception $ex){
				$html .= print_r($ex, true);
			}
			$html .= '</div>';
			//first only
		//	break; 
		//}
		$html .= '</div>';
		$html .= '<script>
$().ready(function(){
	$("#' . $ulId . '")
	  .tabs({
		active: ' . $defaultViewIndex . ',
		beforeLoad: function( event, ui ) {
			
			if ( ui.tab.filter(":not(.onclick-load)").data( "loaded" ) ) {
				event.preventDefault();
				return;
			}
		
			ui.tab.addClass("loading");

			ui.jqXHR.success(function(data) {
				var className = ui.tab.attr("class").replace(/^.*(\\bedq-viewer-[^\\s]*)(\\s.*)?$/, "$1");
				ui.panel.addClass(className);
				ui.tab.filter(":not(.onclick-load)").data( "loaded", true );
				
				ui.tab.removeClass("loading");
			});
			
			ui.jqXHR.error(function(jqXHR, textStatus, errorThrown) {
				ui.panel.html("Impossible de charger le contenu.<br>" + errorThrown );

				ui.tab.removeClass("loading");
			});
		}
	  })'
	  . (is_design() ? '
	  .find(".ui-tabs-nav")
		.sortable({
			axis: "x",
			distance : 8,
			handle: "*", // Selector for the element that is dragable
			update: function (event, ui){
				var value = [];
				$("#' . $ulId . ' > .ui-tabs-nav li").each(function(){
					//alert(this.className); //.replace(/^.*\b(edq-viewer-(\S*)).*$/g, "$2"));
					value.push(this.className.replace(/^.*\b(edq-viewer-(\S*)).*$/g, "$2"));
				});
				data = { 
					"id": ' . $node['id'] . '
					, "vw": "nodeViewer_viewers"
					, "op": "submit"
				};
				data["viewers-sort|"] = JSON.stringify(value);
				//alert(JSON.stringify(data));
				$.ajax({
					url: "' . $this->get_url($node) . '",
					data: data,
					type: "POST"
					//, mode: "abort"
				});
			}
		})
		.end()' : '') . '
	;
 });
 </script>';
		return array(
			"title" => $node['nm']
			, "content" => $html
		);
	}
}

?>