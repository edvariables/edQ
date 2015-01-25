<?php /* Gestion de noeud de requête
UTF8 é

Les paramètres sont dans la table node_param.
"SQLSelect" => SQL
"SQLUpdate" => SQL
"SQLInsert" => SQL
"SQLDelete" => SQL
"Columns" => liste de colonnes ( interprétés via "array(" . $1 . ");" )
	"column" => "titre"
	"column" => {
		"text" => "nom"
		"text" => function($column, $rows, $viewer){ return $column["text"]; }
		"value" => function($row, $column, $viewer){ return $row; }
		"visible" => true
		"attributes" => array()
		"css" => function($column, $viewer){ return ''; }
	}
"Row" => modèle de ligne
	=> function($row, $index, $rows, $viewer){ return '<tr class="' . ($index % 2 === 0 ? 'even' : 'odd') . '">'; }
"Caption" => titre de la table ( interprétés via "return function($node, $rows, $viewer) { [return] " . $1 . "; };" )
"Foot" => pied de table ( interprétés via "return function($node, $rows, $viewer) { [return] " . $1 . "; };" )
*/

/***************
	render
****************/
require_once(dirname(__FILE__) . '/../helpers.php');
require_once(dirname(__FILE__) . '/_class.php');

class nodeViewer_query extends nodeViewer {
	public $domain = 'query';
	public $name = 'query';
	public $text = 'Requête';
		
	/* html
	*/
	public function html($node, $options = false){
		$tabs = '';
		$html = '';
		$textareas = array();
		$uid = uniqid('form-');
		$valueTypes = array();
		// instance de node
		$node = Node::fromClass($this->domain, $node);
		
		foreach($node->parameters($this->domain) as $row){
			/* name */
			$name = ($row["domain"] === null || $row["domain"] === '' ? '' : $row["domain"] . '-')
				. $row["param"]
				. '|';
			/* input */
			switch(strtolower( $row["param"] )){
			case "sqlselect" :
			case "sqlinsert" :
			case "sqlupdate" :
			case "sqldelete" :
				$inputId = uniqid('txt-');
				$textareas[] = array("id" => $inputId, "type" => "sql");
				$value = ifNull($row["value"]);
				$input =	'<textarea id="' . $inputId . '" 
					name="' . $name . '"
					rows="7"
					style="min-width: 700px; width:100%;"
					spellcheck="false">' . $value . '</textarea>'
				;
				break;
			case "caption" :
			case "foot" :
			case "columns" :
				$inputId = uniqid('txt-');
				$textareas[] = array("id" => $inputId, "type" => "html");
				$value = ifNull($row["value"]);
				$input =	'<textarea id="' . $inputId . '" 
					name="' . $name . '"
					rows="7"
					style="min-width: 600px; width:100%;"
					spellcheck="false">' . $value . '</textarea>'
				;
				break;
			default:
				$inputId = uniqid('txt-');
				$value = ifNull($row["value"]);
				$input =	'<textarea id="' . $inputId . '" 
					name="' . $name . '"
					rows="7"
					style="min-width: 400px; width:100%;"
					spellcheck="false">' . $value . '</textarea>'
				;
				break;
			}
			
			$text = $node->label(
				($row["text"] === null || $row["text"] === '' ? $row["param"] : htmlentities( $row["text"] ) ),
				($row["icon"] === null || $row["icon"] === '' ? '(none)' : ( $row["icon"] ) )
				);
			$tabs .= '<li><a href="#' . $inputId . '-panel">'
				. $text
				. '</a></li>';
			$html .= '<div id="' . $inputId . '-panel">' . $input . '</div>';
		}
		
		$html = '<div id="' . $uid . '-tabs"><ul>' . $tabs . '</ul>'
			. $html . '</div>';
		$tabsScript = '$("#' . $uid . '-tabs").tabs({
  beforeLoad: function( event, ui ) {
    if ( ui.tab.filter(":not(.onclick-load)").data( "loaded" ) ) {
      event.preventDefault();
      return;
    }

    ui.jqXHR.success(function() {
      ui.tab.filter(":not(.onclick-load)").data( "loaded", true );
    });
  }
})';
		
		$exists = isset($inputId);
		
		$head = '
		<!-- markItUp! skin -->
		<link rel="stylesheet" type="text/css" href="jquery/markitup/skins/simple/style.css">
		<!--  markItUp! toolbar skin -->';
		foreach($textareas as $textarea){
			$head .= '<link rel="stylesheet" type="text/css" href="jquery/markitup/sets/'. $textarea["type"] .'/style.css">';
		}
		$head .= '<script> if(!$.fn.markItUp){
				$.getScript("jquery/markitup/jquery.markitup.js");
		}</script>'
		/*
		<!-- markItUp! -->
		<script type="text/javascript" src="markitup/jquery.markitup.js"></script>
		*/
		. '<!-- markItUp! toolbar settings -->';
		foreach($textareas as $textarea){
			$head .= '<script type="text/javascript" src="jquery/markitup/sets/'. $textarea["type"] .'/set.js"></script>';
		}
		$script = '<script type="text/javascript">
$().ready(function() {
		// Add markItUp! to your textarea in one line
		var OptionalExtraSettings = {
			previewParserPath: "~/preview.php"
		};';
		foreach($textareas as $textarea){
			$script .= '$("textarea#' . $textarea["id"] . ':first").markItUp(mySettings_' . $textarea["type"] . ', OptionalExtraSettings)
			.parents(".markItUp:first")
				.css("width", "99%")
				.addClass("type-' . $textarea["type"] . '");';
		};

		// toolbar
		$script .= '
			$("#' . $uid . '").find(".toolbar")
				.find("a.add-param") /* ajout d un paramètre */
					.click(function(){
						var param, $param;
						if(!(param = prompt("Nom du paramètre"))) return false;
						if(($param = $(this).parents("form").find(\':input[name="\' + param + \'|"]:first\')).length == 0)
							$param = $(\'<input type="hidden" value="" name="\' + param + \'|"/>\')
								.appendTo($(this).parents("form").find("fieldset:first"));
						$(this).parents("form").submit();
						return false;
					})
					.end()
				.find("a.del-param")
					.click(function(){ /* suppression d un paramètre */
						var $tabs = $(this).parents("form:first").find("fieldset.q-fields .ui-tabs:first");
						var $tab = $tabs.find("> .ui-tabs-nav li.ui-tabs-active:first");
						if($tab.length == 0) return false;
						var selector = $tab.find("a[href]:first").attr("href");
						var $input = $(selector + \' :input[name]:first\');
						var param = $input.attr("name"),
						paramText = param.replace(/query-(.+)\|$/, "$1");
						if(!confirm("Supprimer le paramètre \'" + $tab.text() + "\' [" + paramText + "] ?")) return false;
						var $form = $(this).parents("form");
						var data = { "op" : "delete" };
						var $inputs = $form.find(\'input[name="id"], input[name="vw"], \' + selector + \' :input[name]:first\'); //3 inputs a transmettre
						$inputs.each(function(){ data[this.getAttribute("name")] = this.value; });
						$.ajax({
							type: "POST",
							url: $form.attr("action"),
							data: data
						})
							.done(function(data) {
								if(isNaN(data)){
									$("<div></div>").html(data).dialog({ title: "Suppression impossible" });
									return;
								}
								var activate = $tabs.tabs("option", "active") + 1;
								$tab.remove();
								$tabs.tabs("option", "active", activate);
								$input.parents(".ui-tabs-panel:first").remove();
							})
							.fail(function() {
								alert( "Erreur !" );
							})
						;
;
						return false;
					})
					.end()
			;
		';
	
		$addParam = '<a class="add-param" href="#"'
				. 'style = "margin-left: 1em;"'
			. '>'
			. 'ajouter un paramètre</a>'
		;
	
		$deleteParam = '<a class="del-param" href="#"'
				. 'style = "margin-left: 1em;"'
			. '>'
			. 'supprimer le paramètre</a>'
		;
		
		
		$script .= '
	});'
		. $tabsScript
		. '</script>';

		return array(
			"title" => $node->name
			, "content" => $head
				. '<form id="' . $uid . '" method="post" action="' . $this->get_url($node, 'op') . '">'
					. '<input type="hidden" name="id" value="' . $node->id . '"/>'
					. '<input type="hidden" name="vw" value="' . __CLASS__ . '"/>'
					. '<input type="hidden" name="op" value="submit"/>'
					. '<fieldset class="q-fields">'
					. '<legend>' . $node->label()
						. ($exists ? '' : ' <small><i>(nouvelle requête)</i></small>')
					. '</legend>'
					. '<div>'
					. $html
					. '</div>'
					. '</fieldset>'
					. '<fieldset class="toolbar">'
					. '<input type="submit" value="Enregistrer"/>'
					. $addParam
					. $deleteParam
					. '</fieldset>'
					. '</form>'
				. $this->formScript($uid)
				. $script
		);
	}
}

?>