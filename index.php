<?php
	include('bin/session.php');
	require('tree/helpers.php');
	require('tree/page.php');
?><!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<title>edQ</title>
		<link rel="icon" href="favicon.ico" />
		<meta name="viewport" content="width=device-width" />
		<link rel="stylesheet" href="tree/themes/default/style.css" />
		<link rel="stylesheet" href="css/ui-lightness/jquery-ui-1.10.4.custom.min.css" />

		<link rel="stylesheet" type="text/css" href="css/layout-default-latest.css" />
		
		<link rel="stylesheet" type="text/css" href="jquery/dataTables/css/jquery.dataTables.min.css" />
		
		<link rel="stylesheet" type="text/css" media="screen" href="jquery/jqGrid/css/ui.jqgrid.css" />
		
		<link rel="stylesheet" href="jquery/colorpicker/css/colorpicker.css" type="text/css" />
		<link rel="stylesheet" type="text/css" href="jquery/colorpicker/css/layout.css" />

		<link rel="stylesheet" type="text/css" href="css/edq.css" />

		<script src="js/jquery-1.10.2.js"></script>
		<script src="js/jquery.form.min.js"></script>
		<script src="js/jquery-ui-1.10.4.custom.min.js"></script>
		
		<script type="text/javascript" src="js/jquery.layout-latest.js"></script>
		
		<script type="text/javascript" src="jquery/dataTables/js/jquery.dataTables<?php //echo '.min';?>.js"></script>

		<script src="jquery/jqGrid/js/grid.locale-fr.js" type="text/javascript"></script>
		<script src="jquery/jqGrid/js/jquery.jqGrid.min.js" type="text/javascript"></script>
	
		<script type="text/javascript" src="jquery/colorpicker/js/colorpicker.js"></script>
		
		<script src="tree/jstree.js"></script>
		<script src="tree/jstree.plugins.js"></script>

		<script type="text/javascript" src="jquery/markitup/jquery.markitup.js"></script>
		<script type="text/javascript" src="jquery/markitup/sets/dataSource/set.js"></script>

		<script src="js/edQ.js"></script>
		
		<?php
		if(is_design()) {?>
		<link rel="stylesheet" href="jquery/codemirror/lib/codemirror.css">
		<script src="jquery/codemirror/lib/codemirror.js"></script>
		<script src="jquery/codemirror/addon/edit/matchbrackets.js"></script>
		<script src="jquery/codemirror/mode/php/php.js"></script>
		<script src="jquery/codemirror/mode/htmlmixed/htmlmixed.js"></script>
		<script src="jquery/codemirror/mode/xml/xml.js"></script>
		<script src="jquery/codemirror/mode/javascript/javascript.js"></script>
		<script src="jquery/codemirror/mode/css/css.js"></script>
		<script src="jquery/codemirror/mode/clike/clike.js"></script>
		<?php }?>

		<script type="text/javascript">
		var myLayout;
	
		$(document).ready(function(){
	
			$(".header-footer").hover(
				function(){ $(this).addClass('ui-state-hover'); }
			,	function(){ $(this).removeClass('ui-state-hover'); }
			);
	
			myLayout = $('#container').layout( {
				// RESIZE Accordion widget when panes resize
				west__onresize:  $.layout.callbacks.resizePaneAccordions
				, west__enableCursorHotkey: false
				
				<?php /* mémorisation des tailles et disposition du layout 
					substitution de l'utilisation de cookies par le gestionnaire window.localStorage
					cf jquery.layout.loadState
				*/?>
				, stateManagement__enabled:	false // enable stateManagement - automatic cookie load & save enabled by default
				,	stateManagement__autoLoad:	false // disable automatic cookie-load
				,	stateManagement__autoSave:	false // disable automatic cookie-save
				<?php /*
					charge les paramètres de la page
				*/?>,	onload:	 function(layout, panes){ // run custom state-code when Layout loads
					var data;
					if((data = window.localStorage.getItem('edq-layout')) != null && data != ''){
						data = JSON.parse(data);
						if (!layout.state.initialized) {
							jQuery.extend(layout.options, data[pane]); //, { isClosed: false }
						}
						else  {
							var noAnimate = true;
							for(pane in data){
								//jQuery.extend(panes[pane], data[pane]); //, { isClosed: false }
								var o = data[pane],
								s	= o.size,
								c	= o.isClosed,
								h	= o.isHidden,
								ar	= o.autoResize,
								state	= layout.state[pane],
								open	= state.isVisible;
				
								// reset autoResize
								if (ar)
									state.autoResize = ar;
								else if(s)
									state.autoResize = false; //necessaire sinon bug
								// resize BEFORE opening
								if (!open)
									layout._sizePane(pane, s, false, false, false); // false=skipCallback/noAnimation/forceResize
								// open/close as necessary - DO NOT CHANGE THIS ORDER!
								if (h === true)			layout.hide(pane, noAnimate);
								else if (c === true)	layout.close(pane, false, noAnimate);
								//else if (c === false)	layout.open (pane, false, noAnimate);
								//else if (h === false)	layout.show (pane, false, noAnimate);
								// resize AFTER any other actions
								if (open)
									layout._sizePane(pane, s, false, false, noAnimate); // animate resize if option passed
							}
						}
					}

				}<?php /*
					enregistre les paramètres de la page
				*/?>,	onunload: function(layout, panes){ // ditto when page unloads OR Layout is 'destroyed'
					var data = {}, pane;
					for(pane in layout.panes)
						if(pane != 'center'
						&& layout.panes[pane]){
							pane = panes[pane];
							data[pane.edge] = {
								size : pane.size
								, isClosed : pane.isClosed
								, isHidden : pane.isHidden
								, autoResize : pane.autoResize
							};
						}
					window.localStorage.setItem('edq-layout', JSON.stringify(data));
				}<?php
				/*, stateManagement__autoLoad:	true // automatic cookie-load
				, stateManagement__autoSave:	true // automatic cookie-save*/?>
			} );
	
		});
	</script>
	</head>
	<body id="edQ">
		<div id="container" role="main">
			<div id="favpanel" class="ui-layout-north jstree-default jstree-favpanel" style="text-align: center;">
				<?php include('north.php');?>
			</div>
		
			<div id="inner-wrapper" class="ui-layout-center">
			<div id="data">
				<div class="content default"><center>Choisissez un élément à gauche.</center></div>
			</div>
			</div>
			
			<div class="ui-layout-west">
				<div class="ui-layout-content">
					<div id="tree"></div>
				</div>
			</div>
		</div>
		
		<?php include('tree/jstree.init.php');?>
	</body>
</html>