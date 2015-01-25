<?php

//echo '$arguments : '; var_dump($arguments);

if(!isset($arguments)){
	$arguments = array(
		'node' => '/_docs/edQ - php',
		'node_arguments' => false,
		'title' => 'pour exemple : /_docs/edQ - php',
		'viewer' => 'file.call',
	);
}

if(!isset($arguments['node'])){
	echo __FILE__ . " : aucun noeud";
	return "Aucun noeud";
}

$node = node($node);

$node_ref = node($arguments['node'], $node);

if(!$node_ref){
	echo __FILE__ . " : noeud inconnu " . print_r($arguments['node'], true);
	return "Noeud inconnu";
}

if($node_ref['nm'] == $node['nm']){
	echo __FILE__ . " : récursion sur le noeud";
	return "Récursion sur le noeud";
}

$viewer = isset($arguments['viewer']) ? $arguments['viewer'] : 'file.call';
$tag = isset($arguments['tag']) ? $arguments['tag'] : 'fieldset';
$title = isset($arguments['title']) ? $arguments['title'] : true;
$collapsed = isset($arguments['collapsed']) ? $arguments['collapsed'] : false;
if($title === TRUE)
	$title = $node_ref['nm'];
$node_arguments = isset($arguments['node_arguments']) ? $arguments['node_arguments'] : false;

?><<?=$tag?> class="edq-bloc ui-widget ui-widget-content ui-corner-all
	<?=$collapsed ? 'collapsed' : ''?>"
		 href="<?=node($node_ref, null, 'url')?>">
<?php if($title !== FALSE){
	?><legend class="header-footer ui-state-default ui-corner-all">
		<a href onclick="$(this).nextAll('.edq-toolbar:first').find('.vw--refresh:first').click(); return false;"><?=$title?></a>
		<div class="edq-toolbar">
			<button class="ui-button ui-state-default ui-border-none vw--refresh"
					onclick="$(this).children(':first')
		 						.toggleClass('ui-icon-triangle-1-n').toggleClass('ui-icon-triangle-1-s')
								.parents('.header-footer:first')
							 		.parent().toggleClass('collapsed').end()
		 					;
							return false;"><span class="ui-icon ui-icon-triangle-1-<?=$collapsed ? 's' : 'n'?>" title="masque/affiche"> </span></button>
		</div>
	</legend><?php
}
?><div class="ui-widget-content"><?php
node($node_ref, $node, 'view', $viewer);
?></div></<?=$tag?>>

<style><?php
if($collapsed) {
	?>#<?=$uid?> { display: none; }<?php
}
?><?=node(':css',$node,'call')?></style>