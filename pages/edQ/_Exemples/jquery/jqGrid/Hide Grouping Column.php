<?php
$dataUrl = page::url(':data');

$uid = uniqid('datatable');
if(!isset($arguments))
	$arguments = array();
$arguments[ 'uid' ] = $uid;
page::call(':html', $arguments);

?><script>
$(document).ready(function(){

jQuery("#<?=$uid?>").jqGrid({
	data: <?php page::execute(':data', __FILE__)?>,
	datatype: 'local',
	height: 'auto',
	rowNum: 30,
	rowList: [10,20,30],
   	colNames:['Inv No', 'Date', 'Client', 'Amount','Tax','Total','Notes'],
   	colModel:[
   		{name:'id',index:'id', width:60, sorttype:"int"},
   		{name:'invdate',index:'invdate', width:90, sorttype:"date", formatter:"date"},
   		{name:'name',index:'name', width:100, editable:true},
   		{name:'amount',index:'amount', width:80, align:"right",sorttype:"float", formatter:"number", editable:true},
   		{name:'tax',index:'tax', width:80, align:"right",sorttype:"float", editable:true},		
   		{name:'total',index:'total', width:80,align:"right",sorttype:"float"},		
   		{name:'note',index:'note', width:150, sortable:false}		
   	],
   	pager: "#<?=$uid?>-nav",
   	viewrecords: true,
   	sortname: 'name',
   	grouping:true,
   	groupingView : {
   		groupField : ['name'],
   		groupColumnShow : [false]
   	},
   	caption: "Colonne de regroupement masquée"
});
});
</script>