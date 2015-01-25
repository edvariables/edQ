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
		xDimension : [{dataName: 'name'}
					, {dataName: 'invdate'}
					],
		yDimension : [],
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
	},
	// grid options
	{
		height: 'auto',
		rowNum: 30,
		rowList: [10,20,30],
		/* la définition des colonnes masque les sous-totaux */
		colNames:['Client', 'Date','CA','Nbre'],
		colModel:[
			{name:'name',index:'name', width:100, editable:true},
			{name:'invdate',index:'invdate', width:90, sorttype:"date", formatter:"date"},
			{name:'amount_sum',index:'amount_sum', width:80, align:"right",sorttype:"float", formatter:"number", editable:false},
			{name:'amount_count',index:'amount_count', width:80, align:"right",sorttype:"float", formatter:"number", editable:false},
		],/**/
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