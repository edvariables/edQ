<?php
helpers::need_plugin('jqGrid');

$node = node($node, __FILE__);
$uid = uniqid('pivot');
if(!isset($arguments))
	$arguments = array();
$arguments[ 'uid' ] = $uid;
page::call(':html', $arguments, $node);
?>
<script type="text/javascript">
	jQuery(document).ready(function(){ 
		jQuery("#grid").jqGrid('jqPivot',
		'<?= page::url(':data', $node) ?>', 
		// pivot options
		{
							   xDimension : [{dataName: 'CategoryName'} ], 
							   yDimension : [], 
							   aggregates : [ { 
								   member : 'Price', 
								   aggregator : 'sum', 
								   width:80, label:'Sum Price', 
								   formatter:'number', 
								   align:'right' 
							   } ] 
		}, 
		// grid options
		{ 
							   width: 700, 
							   rowNum : 150, 
							   pager: "#pager", 
							   caption: "Amounts of each product category"
		});
}); </script>

<script>
$(document).ready(function(){

jQuery("#<?=$uid?>").jqGrid('jqPivot',
	'<?= page::url(':data', $node) ?>',
	//< ?php node(':data', $node, 'call'); ? >,//
	// pivot options
	{ 
		xDimension : [{dataName: 'name'} ],
		yDimension : [],
		aggregates : [ { 
			member : 'amount', 
			aggregator : 'sum', 
			width:80, 
			label:'CA', 
			formatter:'number', 
			align:'right' }
		]
	},
	// grid options
	{
		datatype: "json",
		height: 'auto',
		rowNum: 30,
		rowList: [10,20,30,999],
		/*colNames:['Client','CA'],
		colModel:[
			{name:'name',index:'name', width:100, editable:true},
			{name:'CA',index:'CA', width:80, align:"right",sorttype:"float", formatter:"number", editable:false},
		],*/
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
		viewrecords: false,
		//sortname: 'name',
		caption: "Pivot"
	});
});
</script>