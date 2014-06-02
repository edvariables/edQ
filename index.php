<?php
	include('bin/session.php');
?><!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<title>edQ</title>
		<meta name="viewport" content="width=device-width" />
		<link rel="stylesheet" href="tree/themes/default/style.css" />
		<!--link rel="stylesheet" href="css/smoothness/jquery-ui-1.10.4.custom.min.css" /-->
		<link rel="stylesheet" href="css/ui-lightness/jquery-ui-1.10.4.custom.min.css" />

		<link rel="stylesheet" type="text/css" href="css/layout-default-latest.css" />
		
		<link rel="stylesheet" type="text/css" href="css/edq.css" />
		<style type="text/css">
		/* layout : Using an 'optional-container' instead of 'body', so need body to have a 'height' */
		html, body {
			width:		100%;
			height:		100%;
			padding:	0;
			margin:		0;
			overflow:	hidden !important;
		}
		#container {
			width:			98%;
			height:			96%;
			margin-top:		1%;
			margin-left:	1%;
		}
		#inner-wrapper {
				overflow: auto;
		}
		.ui-layout-center { overflow: hidden; }
		.ui-layout-north > button {
			float: right;
			border: 1px outset silver;
			background: none;
			background-color: white;
			font-size: smaller;
			margin-left: 6px;
			margin-top: 6px;
			cursor: pointer;
		}
		.ui-layout-north > button:first-of-type {
			margin-right: 20em;
		}
		.ui-layout-pane {
			border-radius: 4px;
		}
		</style>
		<style>
		/* tree */
		html, body { background:#ebebeb; font-size:10px; font-family:Verdana; margin:0; padding:0; }
		/*#container { min-width:320px; margin:0px auto 0 auto; background:white; border-radius:0px; padding:0px; overflow:hidden; }*/
		#tree { float:left; /*min-width:319px; border-right:1px solid silver;*/ overflow:auto; padding:0px 0; }
		/*#data { margin-left:320px; }*/
		/*#data textarea { margin:0; padding:0; height:100%; width:100%; border:0; background:white; display:block; line-height:18px; }*/
		#data { font: normal normal normal 12px/18px 'Consolas', monospace !important; }
		
		#data pre { line-height: 1em; text-align: left; padding-left: 1em; }
		
		.jstree-contextmenu {
			z-index: 9999; /* concurence avec layout */
		}
		.ui-tabs-anchor .jstree-icon {
			margin-right: 3px;
		}
		</style>

		<script src="js/jquery-1.10.2.js"></script>
		<script src="js/jquery.form.min.js"></script>
		<script src="js/jquery-ui-1.10.4.custom.min.js"></script>
		
		<script type="text/javascript" src="js/jquery.layout-latest.js"></script>
		
		<script src="tree/jstree.js"></script>

<script type="text/javascript" src="markitup/jquery.markitup.js"></script>
<script type="text/javascript" src="markitup/sets/dataSource/set.js"></script>

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
				,	onload:	 function(layout, panes){ // run custom state-code when Layout loads
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

				}
				,	onunload: function(layout, panes){ // ditto when page unloads OR Layout is 'destroyed'
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
				}
				/*, stateManagement__autoLoad:	true // automatic cookie-load
				, stateManagement__autoSave:	true // automatic cookie-save*/
			} );
	
		});
		</script>
	</head>
	<body>
		<div id="container" role="main">
			<div class="ui-layout-north" style="text-align: center;">
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