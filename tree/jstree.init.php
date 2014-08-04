<?php
$_SESSION['tree-root'] = isset($_REQUEST["tree-root"]) ? $_REQUEST["tree-root"] : "#";
require('tree/nodeType/_class.php');
$design = is_design();
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
					'url' : 'tree/db.php?operation=get_node<?= $design ? '&design=1' : '' ?>',
					'data' : function (node) {
						return { 'id' : node.id };
					}
				},
				'check_callback' : true,
				'themes' : {
					'responsive' : false
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
			"types" : <?= json_encode(node::get_types()) ?>,
			"icons" : <?= json_encode(node::get_icons()) ?>,
			"ulvls" : <?= json_encode(node::get_ulvls()) ?>,
			'plugins' : ['state','dnd','contextmenu','wholerow', 'types', 'edQ', 'favpanel']
		})
		.on('delete_node.jstree', function (e, data) {
			if(!confirm("Supprimer ?")){
				data.instance.refresh();
				return false;
			}
			$.get('tree/db.php?operation=delete_node', { 'id' : data.node.id })
				.fail(function () {
					data.instance.refresh();
				});
		})
		.on('create_node.jstree', function (e, data) {
			$.get('tree/db.php?operation=create_node', { 'id' : data.node.parent, 'position' : data.position, 'text' : data.node.text, 'icon' : data.node.icon, 'type' : data.node.type })
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
				$.get('tree/db.php?operation=rename_node', { 'id' : data.node.id, 'text' : data.text })
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
			$.get('tree/db.php?operation=update_node', jQuery.extend( { 'id' : data.node.id }, data.properties ))
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
					url: 'tree/db.php?operation=edit_node'
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
			$.get('tree/db.php?operation=move_node', { 'id' : data.node.id, 'parent' : data.parent, 'position' : data.position })
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
			$.get('tree/db.php?operation=copy_node', { 'id' : data.original.id, 'parent' : data.parent, 'position' : data.position })
				.always(function () {
					data.instance.refresh();
				});
		})
		.on('changed.jstree', function (e, data) {
			if(data && data.selected && data.selected.length) {
				$.get('tree/db.php?operation=get_view'
					+ '&id=' + data.selected.join(':')
					+ '&vw=viewers<?=
						$design ? '&design=true' : '' ?>'
				, function (d) {
					$('#data .default').html(d.content).show();
				});
			}
			else {
				$('#data .content').hide();
				$('#data .default').html('<center>S&eacute;lectionnez un &eacute;l&eacute;ment &agrave; gauche</center>').show();
			}
		});
});</script>