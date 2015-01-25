<?php
$_SESSION['tree-root'] = isset($_REQUEST["tree-root"]) ? $_REQUEST["tree-root"] : "#";
include_once('tree/nodeType/_class.php');
$design = is_design();
if(isset($_REQUEST['id'])){
	$_REQUEST['op'] = true;
	include_once('tree/db.php');
	if(!is_numeric($_REQUEST['id'])
	   && $_REQUEST['id'][0] != '/')
		$_REQUEST['id'] = '/' . $_REQUEST['id'];
	try{
		$node = node($_REQUEST['id'], null, array('with_path' => true));
	}
	catch(Exception $ex){
		$node = false;
		//$alert = $ex->xdebug_message;
		$alert = '<h1 style="color: red">Le noeud "' . $_REQUEST['id'] . '" n\'existe pas.</h1>';
	}
}
else
	$node = false;
?><script>
$().ready(function () {
	// $(window).resize(function () {
		// var h = Math.max($(window).height() - 0, 420);
		// $('#container, #data, #tree, #data .content').height(h).filter('.default').css('lineHeight', h + 'px');
	// }).resize();

	$('#tree')
		.jstree({
			'core' : {
				'data' : {
					'url' : 'tree/db.php?op=get_node<?= $design ? '&design=1' : '' ?>',
					'data' : function (node) {
						return { 'id' : node.id };
					}
				},
				'check_callback' : true,
				'themes' : {
					'responsive' : false
					, 'dir' : '../resources/tree/' /* ED140926 changed folder location */
				}
			},
			"design" : <?= $design ? '1' : '0' ?>,
			"defaults" : { <?php /* cf plugins.edQ */ ?>
				"dnd" : {
					"move" : <?= $design ? '1' : '0' ?>,
					"copy" : <?= $design ? '1' : '0' ?>,
					"always_copy" : false
				}
				, "favpanel" : {
					"design" : <?= $design ? '1' : '0' ?>,
					"selector" : ".jstree-favpanel"
				}
			},
			<?php /* ED141012 */
			if($node){
			?>"state" : {
				"node" : {
					'id' : <?=$node['id']?>
					, 'path' : <?=json_encode(node($node, null, 'path_ids'))?>
				}
			},
			<?php } ?>
			<?php /* ED141012 */
			if(isset($alert) && $alert){
			?>"edQ" : {
				"alert" : <?=json_encode($alert)?>
			},
			<?php } ?>
			"types" : <?= json_encode(Node::get_types()) ?>,
			"icons" : <?= json_encode(Node::get_icons()) ?>,
			"ulvls" : <?= json_encode(Node::get_ulvls()) ?>,
			'plugins' : ['state','dnd','contextmenu','wholerow', 'types', 'edQ', 'favpanel']
		})
		.on('delete_node.jstree', function (e, data) {
			if(!confirm("Supprimer ?")){
				data.instance.refresh();
				return false;
			}
			$.get('tree/db.php?op=delete_node', { 'id' : data.node.id })
				.fail(function () {
					data.instance.refresh();
				});
		})
		.on('create_node.jstree', function (e, data) {
			$.get('tree/db.php?op=create_node', { 'id' : data.node.parent, 'position' : data.position, 'text' : data.node.text, 'icon' : data.node.icon, 'type' : data.node.type })
				.done(function (d) {
					data.instance.set_id(data.node, d.id);
					if(data.node.original.text != d.nm){
						data.instance.set_text(data.node, d.nm, false);
						setTimeout(function () { data.instance.edit(d); },0);
					}
				})
				.fail(function () {
					data.instance.refresh();
				});
		})
		.on('rename_node.jstree', function (e, data) {
			if(data.toServer || data.toServer === undefined) {
				$.get('tree/db.php?op=rename_node', { 'id' : data.node.id, 'text' : data.text })
					.done(function (d) {
					//mise a jour du noeud
					if(d.nm
					&& (data.node.text != d.nm)){
						data.node.text = d.nm;
						data.instance.refresh_node( data.node );
					}
				})
				.fail(function () {
					data.instance.refresh();
				});
			}
		})
		.on('update_node.jstree', function (e, data) {
			$.get('tree/db.php?op=update_node', jQuery.extend( { 'id' : data.node.id }, data.properties ))
				.done(function () {
					data.instance.trigger('changed', { 'action' : 'select_node', 'node' : data.node, 'selected' : [data.node.id], 'event' : null });
				})
				.fail(function () {
					data.instance.refresh();
				});
		})
		/* ED
		*/
		.on('edit_node.jstree', function (e, data) {
			jQuery
				.ajax({
					url: 'tree/db.php?op=edit_node'
					, data: { 'id' : data.node.id }
					, async: false
				})
				.done(function (d) {
					if(d === 'object')
						//mise a jour du noeud
						for(var prop in d[0])
							if(prop != "id" || prop != "children") 
								//TODO prop == "data" : écrase la propriété data
								data.node[prop] = d[0][prop];
				})
				.fail(function () {
					data.instance.refresh();
				});
		})
		
		.on('move_node.jstree', function (e, data) {
			$.get('tree/db.php?op=move_node', { 'id' : data.node.id, 'parent' : data.parent, 'position' : data.position })
				.done(function (d) {
					//mise a jour du noeud
					if(d.nm
					&& (data.node.text != d.nm)){
						data.node.text = d.nm;
						data.instance.refresh_node( data.node );
					}
				})
				.fail(function () {
					data.instance.refresh();
				});
		})
		.on('copy_node.jstree', function (e, data) {
			$.get('tree/db.php?op=copy_node', { 'id' : data.original.id, 'parent' : data.parent, 'position' : data.position })
				.always(function () {
					data.instance.refresh();
				});
		})
		.on('changed.jstree', function (e, data) {
			if(data && data.selected && data.selected.length) {
				$.get('tree/db.php?op=get_view'
					+ '&id=' + data.selected.join(':')
					+ '&vw=viewers<?=
						$design ? '&design=true' : '' ?>'
				, function (d) {
					<?php /* ED141012
					       * gestion des erreurs lorsque l'onglet Affichage est le 1er à gauche
					       */
					?>
					if (typeof d === "string") {//error
						<?php
						if ($design) {//Add tab reset 
							?>
							d = '<a href="tree/nodeViewer/viewers.php?vw=nodeViewer_viewers&op=submit&viewers-sort|=[]&id=' + data.selected.join(':') + '"'
							  + ' onclick="$.get(this.href);'
							  + '  $(this).after(\'<div>Ok, vous pouvez rafraichir la page.</div>\');'
							  + '  return false;">r&eacute;initialiser les onglets du noeud</a>'
							  + '<br/>'
							  + d;
						<?php
						}
						else { ?>
							d = "d&eacute;sol&eacute;, une erreur est survenue."
							  + '<br/>'
							  + d;
						<?php }?>
						$('#data .default').html(d).show(); 
					}
					else //json
						$('#data .default').html(d.content).show();
				});
			}
			else {
				$('#data .content').hide();
				$('#data .default').html('<center>S&eacute;lectionnez un &eacute;l&eacute;ment &agrave; gauche</center>').show();
			}
		});
});</script>