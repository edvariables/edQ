<?php
	include('inc/session.php');
	if(!$_SESSION['edq-user'] || $_SESSION['edq-user']['UserType'] == 1024){
		unset( $_SESSION['edq-user'] );
		include('inc/session.php');
	}
	
	require('tree/helpers.php');
	require('tree/page.php');
	
	$edq_plugins = helpers::init_plugins(
		array(
		'jstree' => false,
		'flot' => false,
		'jqGrid' => false,
		'colorpicker' => false,
		'dataTables' => false,
		'markitup' => false,
		'codemirror' => false,
		)
	);
	if( is_design() ){
	    $edq_plugins = array_merge( $edq_plugins,
		array(
		    'colorpicker' => true,
		    'codemirror' => true,
		)
	    );
	}
	if(strpos($_SERVER["PHP_SELF"], 'index.php') !== FALSE)
		$edq_plugins['jstree'] = true;
		
?><!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<title>Le Moulin</title>
		<link rel="icon" href="favicon.ico" />
		<meta name="viewport" content="width=device-width" />
		<link rel="stylesheet" href="res/tree/themes/default/style.css" />
		<link rel="stylesheet" href="res/css/ui-lightness/jquery-ui-1.10.4.custom.min.css" />

		<link rel="stylesheet" type="text/css" href="res/css/layout-default-latest.css" />
		
		<?php if($edq_plugins['dataTables']){?>
		<link rel="stylesheet" type="text/css" href="res/jquery/dataTables/css/jquery.dataTables.min.css" />
		<?php }?>
		<?php if($edq_plugins['jqGrid']){?>
		<link rel="stylesheet" type="text/css" media="screen" href="res/jquery/jqGrid/css/ui.jqgrid.css" />
		<?php }?>
		
		<?php if($edq_plugins['colorpicker']){?>
		<link rel="stylesheet" href="res/jquery/colorpicker/css/colorpicker.css" type="text/css" />
		<link rel="stylesheet" type="text/css" href="res/jquery/colorpicker/css/layout.css" />
		<?php }?>

		<link rel="stylesheet" type="text/css" href="css/edq.css" />

		<script src="res/js/jquery-1.10.2.js"></script>
		<script src="res/js/jquery.form.min.js"></script>
		<script src="res/js/jquery-ui-1.10.4.custom.min.js"></script>
		
		<script type="text/javascript" src="res/js/jquery.layout-latest.js"></script>
		
		<?php if($edq_plugins['dataTables']){?>
		<script type="text/javascript" src="res/jquery/dataTables/js/jquery.dataTables<?php //echo '.min';?>.js"></script>
		<?php }?>

		<?php if($edq_plugins['jqGrid']){?>
		<script src="res/jquery/jqGrid/js/grid.locale-fr.js" type="text/javascript"></script>
		<script src="res/jquery/jqGrid/js/jquery.jqGrid.min.js" type="text/javascript"></script>
		<?php }?>
	
		<?php if($edq_plugins['colorpicker']){?>
		<script type="text/javascript" src="res/jquery/colorpicker/js/colorpicker.js"></script>
		<?php }?>
		
		<?php if($edq_plugins['jstree']){?>
		<script src="tree/jstree.js"></script>
		<script src="tree/jstree.plugins.js"></script>
		<?php }?>

		<?php if($edq_plugins['markitup']){?>
		<script type="text/javascript" src="res/jquery/markitup/jquery.markitup.js"></script>
		<script type="text/javascript" src="res/jquery/markitup/sets/dataSource/set.js"></script>
		<?php }?>

		<?php if($edq_plugins['flot']){?>
		<!--[if IE]><script language="javascript" type="text/javascript" src="../excanvas.pack.js"></script><![endif]-->
		<script language="javascript">if(!$.browser) $.browser = {};</script>
		<!--[if IE]><script language="javascript">$.browser.msie = true;</script><![endif]-->
		<script language="javascript" type="text/javascript" src="res/jquery/flot/jquery.flot.js"></script>
		<?php }?>
    
		<script src="js/edQ.js"></script>
		
		<?php
		if($edq_plugins['codemirror']) {?>
		<link rel="stylesheet" href="res/jquery/codemirror/lib/codemirror.css">
		<script src="res/jquery/codemirror/lib/codemirror.js"></script>
		<script src="res/jquery/codemirror/addon/edit/matchbrackets.js"></script>
		<script src="res/jquery/codemirror/mode/php/php.js"></script>
		<script src="res/jquery/codemirror/mode/htmlmixed/htmlmixed.js"></script>
		<script src="res/jquery/codemirror/mode/xml/xml.js"></script>
		<script src="res/jquery/codemirror/mode/javascript/javascript.js"></script>
		<script src="res/jquery/codemirror/mode/css/css.js"></script>
		<script src="res/jquery/codemirror/mode/clike/clike.js"></script>
		<?php }
	
		if (!isset($_REQUEST['inneronly'])) {
		?>

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
		</script><?php
		}?>
	</head>
	<body id="edQ">
		<div id="container" role="main"><?php
		if (isset($_REQUEST['inneronly'])) {?>
			<div id="data" class="inneronly ui-layout-pane">
				<div class="edq-viewers ui-widget ui-widget-content content default">
					<?php include('view.php');?>
				</div>
			</div><?php
		} else {
		?>
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
		<?php 	
		include('tree/jstree.init.php');
		}?></div>
	</body>
</html>