<?php
$node = node($node, __FILE__);
$sql = "SELECT *
	FROM tree_data
	LIMIT 999";
$db = get_db('/_System/dataSource');

$arguments = array(
	'rows' => $db->all( $sql )
	, 'pivot' => true
	, 'columns' => array(
		'id' => array(
			'label' => 'noeuds',
			'group' => false,
			'count' => true,
			'formatter' => 'function(x){if(x) return x; return "";}',
		),
		'ulvl' => array(
			'label' => 'Niveau utilisateur',
			'type' => 'integer',
			'sorttype' => 'integer',
		),
		'typ' => array(
			'label' => 'Type',
			'group' => 'x',
		),
		'icon' => array(
			'label' => 'Icône',
			'group' => 'y',
		)
	)
);
$uid = uniqid('form');
?>
<form id="<?=$uid?>" method="POST" action="<?=page::url( $node )?>" autocomplete="off" style="margin-bottom: 2em;">
	<fieldset><legend><?=$node['nm']?></legend>
		<?php $arguments = page::call('/_html/table/rows/jqGrid/toolbar', $arguments);
		/*echo '<pre>';
		var_dump($arguments['columns']);
		echo '</pre>';*/?>
	</fieldset><?php
	/* submit */
		?><input type="submit" value="Rechercher" style="margin: 0.5em;"/><?php
	if( $cur_table ) {
		/* télécharger */
		$viewer = tree::get_id_by_name('/_format/csv/from rows');
		$viewer_options = "&node=" . $node['id']
			. "&file--name=" . $cur_table
			. "&f--table=" . $cur_table
			. "&node--get=" . 'rows';
		?><a class="file-download" href="view.php?id=<?=$viewer?><?=$viewer_options?>&vw=file.call" style="margin-left: 2em;">télécharger le .csv</a><?php
	}
	?>
</form>
<?= page::form_submit_script($uid)?>
<?php
page::call('/_html/table/rows/jqGrid', $arguments);
?>