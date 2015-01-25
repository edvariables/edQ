<?php
helpers::need_plugin('jqGrid');

$node = node($node, __FILE__);
$uid = uniqid('pivot');
if(!isset($arguments))
	$arguments = array();
$arguments[ 'uid' ] = $uid;
page::call(':html', $arguments, $node);
?><script>
$(document).ready(function(){

jQuery("#<?=$uid?>").jqGrid('jqPivot',
	//'<?= page::url(':data', $node) ?>',
	<?php node(':data', $node, 'call');	?>,
	// pivot options
	{ 
		xDimension : [{dataName: 'name', width:90}
					, {dataName: 'invdate', width:90}
		],
		yDimension : [
					{dataName: 'note'}
		],
		aggregates : [ { 
			member : 'amount', 
			aggregator : 'sum', 
			width:80, 
			formatter:'number', 
			align:'right',
			summaryType: 'sum'
		}, 
		{ 
			member : 'amount', 
			aggregator : 'count', 
			width:80, 
			formatter:'number', 
			align:'right',
			summaryType: 'count'
		}
		]
		, groupSummaryPos : 'footer'
		, rowTotals: true
		, colTotals: true
		, frozenStaticCols : true
	},
	// grid options
	{
		height: 'auto',
							
		width: '600',
		//autowidth: true,
		shrinkToFit: false,
							
		rowNum: 30,
		rowList: [10,20,30],
		/* la définition des colonnes masque les sous-totaux */
		colNames:['Client', 'Date','CA','Nbre','CA','Nbre','CA','Nbre', 'Total','Nbre'],
		colModel:[
			{name:'name',index:'name', width:100, editable:true, frozen : true},
			{name:'invdate',index:'invdate', width:90, sorttype:"date", formatter:"date", frozen : true},
			{name:'note1_sum_0',index:'note1_sum_0', width:80, align:"right",sorttype:"float", formatter:"number", editable:false},
			{name:'note1_count_1',index:'note1_count_1', width:80, align:"right",sorttype:"float", formatter:"number", editable:false},
			{name:'note2_sum_0',index:'note2', width:80, align:"right",sorttype:"float", formatter:"number", editable:false},
			{name:'note2_count_1',index:'note2_count_1', width:80, align:"right",sorttype:"float", formatter:"number", editable:false},
			{name:'note3_sum_0',index:'note3_sum_0', width:80, align:"right",sorttype:"float", formatter:"number", editable:false},
			{name:'note3_count_1',index:'note3_count_1', width:80, align:"right",sorttype:"float", formatter:"number", editable:false},
			{name:'_r_Totals_sum_0',index:'_r_Totals_sum_0', width:80, align:"right",sorttype:"float", formatter:"number", editable:false},
			{name:'_r_Totals_count_1',index:'_r_Totals_count_1', width:80, align:"right",sorttype:"float", formatter:"number", editable:false},
		],
		/*colNames:['Inv No','Date', 'Client', 'Amount','Tax','Total','Notes', 'CA'],
		colModel:[
			{name:'id',index:'id', width:60, sorttype:"int"},
			{name:'invdate',index:'invdate', width:90, sorttype:"date", formatter:"date"},
			{name:'name',index:'name', width:100, editable:true},
			{name:'amount',index:'amount', width:80, align:"right",sorttype:"float", formatter:"number", editable:true},
			{name:'tax',index:'tax', width:80, align:"right",sorttype:"float", editable:true},		
			{name:'total',index:'total', width:80,align:"right",sorttype:"float"},		
			{name:'note',index:'note', width:150, sortable:false},			
			{name:'CA',index:'CA', width:80, align:"right",sorttype:"float", formatter:"number", editable:false},
		],*/
		pager: "#<?=$uid?>-nav",
		sortname: 'amount_count',
		caption: "Pivot"
	});
});
</script>