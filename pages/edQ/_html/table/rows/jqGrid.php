<?php
/* http://www.trirand.com/jqgridwiki/doku.php?id=wiki:colmodel_options */
helpers::need_plugin('jqGrid');

$node = node($node, __FILE__);
if(!isset($arguments)){
	$arguments = array(
		'rows' => json_decode(node('/_Exemples/jquery/jqGrid/Pivot/lignes/data', $node, 'content'), false)
	);
	if($arguments['rows'] == NULL)
		echo 'Attention, pour json_decode, les index doivent être entre ""';
};

	
if(!isset($arguments['rows']))
	die("argument rows manquant");

if(!isset($arguments[ 'uid' ])){
	$uid = uniqid('jqgrid');
	$arguments[ 'uid' ] = $uid;
}
else
	$uid = $arguments[ 'uid' ];
page::call(':html', $arguments, $node);
?><script>
$(document).ready(function(){

<?php 

/* colonnes */
if(isset($arguments['columns'])){
	$columns = $arguments['columns'];
	if(!isset($columns['*']))
		$columns['*'] = array();
}
else
	$columns = false;

/* script $.jqGrid */
if(isset($arguments[ 'pivot' ])){
	if(!is_array($arguments[ 'pivot' ]))
		$arguments[ 'pivot' ] = array();
	?>
	var pivotOptions = jQuery.extend(true, { 
			xDimension : [],
			yDimension : [],
			aggregates : []
			//, groupSummaryPos : 'footer'
			, rowTotals: true
			, colTotals: true
		}, <?=json_encode( $arguments[ 'pivot' ] )?>);
	var colNames = [];
	var colNamesAggreg = [];
	var colNamesCancel = false;
	<?php
	if($columns){
		foreach($columns as $columnName => $column){
			if(isset($column['label']))
				$columnLabel = $column['label'];
			else
				$columnLabel = $columnName;
			/* regrouper par cette colonne */
			if(isset($column['group']) && $column['group']){
				if(is_string($column['group']) && $column['group'] == 'y'){ ?> 
					pivotOptions.yDimension.push( 
							$.extend(<?=json_encode($column)?>, {dataName: '<?=$columnName?>'} )
					);
					colNamesCancel = true; /* TODO autant de colonnes que de valeurs distinctes */
				<?php
				}
				else { ?>
					pivotOptions.xDimension.push( 
							$.extend(<?=json_encode($column)?>, {dataName: '<?=$columnName?>'} )
					);
				<?php
				}?>
				colNames.push(<?=json_encode($columnLabel)?>);
				<?php
			}
			/* calculer la somme de cette colonne */
			if(isset($column['sum']) && $column['sum']){
				?> colNamesAggreg.push(<?=json_encode('Somme de ' . $columnLabel)?>);
				pivotOptions.aggregates.push( jQuery.extend({ 
					member : '<?=$columnName?>', 
					aggregator : 'sum', 
					width:80, 
					formatter:'number', 
					align:'right',
					summaryType: 'sum'
				}, <?= json_encode($column)?>) );<?php
			}
			if(isset($column['count']) && $column['count']){
				?> colNamesAggreg.push(<?=json_encode('Nbre de ' . $columnLabel)?>);
				pivotOptions.aggregates.push( jQuery.extend({ 
					member : '<?=$columnName?>', 
					aggregator : 'count', 
					width:80, 
					formatter:'string', 
					align:'right',
					summaryType: 'sum' /* ou count, dépend du nbre de groupes */
				}, <?= json_encode($column)?>) );<?php
			}
			if(isset($column['avg']) && $column['avg']){
				?> colNamesAggreg.push(<?=json_encode('Moy. de ' . $columnLabel)?>);
				pivotOptions.aggregates.push( jQuery.extend({ 
					member : '<?=$columnName?>', 
					aggregator : 'avg', 
					width:80, 
					formatter:'number', 
					align:'right',
					summaryType: 'avg'
				}, <?= json_encode($column)?>) );<?php
			}
		}
	}
	?>
	/* Les propriétés pivotOptions.aggregates[].formatter peuvent être des function.
	 * Elles commencent par function(.
	 * Evalue pivotOptions.aggregates[].formatter.
	 */
	for(var i = 0; i < pivotOptions.aggregates.length; i++)
		if(typeof pivotOptions.aggregates[i].formatter === 'string'
		&& /^function\s*\(/.test(pivotOptions.aggregates[i].formatter))
			pivotOptions.aggregates[i].formatter = eval('(' + pivotOptions.aggregates[i].formatter + ')');
	
	/* Noms des colonnes */
	colNames = colNamesCancel ? undefined : colNames.concat(colNamesAggreg);
	/* gridOptions */
	var gridOptions = {
		height: 'auto',

		width: 'auto',		
		shrinkToFit: true,

		rowNum: 30,
		rowList: [10,20,30,100,9999],
		pager: "#<?=$uid?>-nav",
		caption: "Résultat",

		colNames: colNames
	};
	<?php
	if(isset($arguments[ 'grid' ])){
		?>
		gridOptions = jQuery.extend(gridOptions, <?=json_encode($arguments[ 'grid' ])?>);
	<?php }
	?> /* exec */
	jQuery("#<?=$uid?>").jqGrid('jqPivot',
		//'<?= page::url(':data', $node) ?>',
		<?= is_array($arguments['rows']) ? json_encode($arguments['rows']) : $arguments['rows'] ?>,
		// pivot options
		pivotOptions ,
		// grid options
		gridOptions
	);
<?php 
}
else if(!isset($arguments[ 'pivot' ])){
?>
	var rows = <?= is_array($arguments['rows']) ? json_encode($arguments['rows']) : $arguments['rows'] ?>;
	var $table = jQuery("#<?=$uid?>");
	<?php
	
	$colModels = array();
	if(count($arguments['rows'])){
		foreach($arguments['rows'][0] as $column => $value){
			$colModel = array(
				'name' => $column,
				'index' => $column,
			);
			if($columns)
				if(isset($columns[$column]))
					$colModel = array_merge($colModel, $columns['*'], $columns[$column]);
				else
					$colModel = array_merge($colModel, $columns['*']);
			$colModels[] = $colModel;
			
		}
	}
	?>
	$table.jqGrid(
		{
			datatype: "local",
			height: 'auto',
			width: 'auto',
			autowidth: true,
			shrinkToFit: true,
			rowNum: 20,
			rowList: [10,20,30,100,9999],
			colModel:<?=json_encode($colModels)?>,
			pager: "#<?=$uid?>-nav",
		}
	);
	for(var i=0;i<=rows.length;i++)
		$table.jqGrid('addRowData',i+1,rows[i]);
<?php 
}
?>});
</script>