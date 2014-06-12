<?php
$dataUrl = substr(__FILE__, 0, strlen(__FILE__) - 4);
$root = preg_replace('/\\$/', '', $_SERVER['DOCUMENT_ROOT']);
$root = '/' . str_replace('\\', '/', substr($dataUrl, strlen($root)));
$dataUrl = $root . '/data.php';
?>
<?php include(substr(__FILE__, 0, strlen(__FILE__) - 4) . '/html.php'); ?>
<script>
$(document).ready(function(){

var mydata = <?php include(substr(__FILE__, 0, strlen(__FILE__) - 4) . '/data.php'); ?>;

jQuery("#list482").jqGrid({
	data: mydata,
	datatype: "local",
	height: 'auto',
	rowNum: 30,
	rowList: [10,20,30],
   	colNames:['Inv No','Date', 'Client', 'Amount','Tax','Total','Notes'],
   	colModel:[
   		{name:'id',index:'id', width:60, sorttype:"int"},
   		{name:'invdate',index:'invdate', width:90, sorttype:"date", formatter:"date"},
   		{name:'name',index:'name', width:100, editable:true},
   		{name:'amount',index:'amount', width:80, align:"right",sorttype:"float", formatter:"number", editable:true},
   		{name:'tax',index:'tax', width:80, align:"right",sorttype:"float", editable:true},		
   		{name:'total',index:'total', width:80,align:"right",sorttype:"float"},		
   		{name:'note',index:'note', width:150, sortable:false}		
   	],
   	pager: "#plist482",
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