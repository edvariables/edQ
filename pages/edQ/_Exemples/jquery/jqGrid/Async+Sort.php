<?php
$dataUrl = url_page(':data');

$uid = uniqid('datatable');
call_page(':html', array( 'uid' => $uid ));

?><script>
$(document).ready(function(){

jQuery("#<?=$uid?>").jqGrid({
	datatype: function(postdata) {
	    jQuery.ajax({
	 	 url: '<?=url_page(':data')?>',
	 	 data: postdata,
	 	 dataType:"text",
	 	 success: function(response,stat){
	 	 	 var $thegrid = jQuery("#<?=$uid?>");
	 	 	 var data = eval( '(' + response + ')' );
	 	 	 //tri côté client
	 	 	 var fields = postdata.sidx.split(',');
	 	 	 var sords = [];
	 	 	 for(var i = 0; i < fields.length; ++i){
	 	 	 	 var pos;
	 	 	 	 if(pos = fields[i].indexOf(' ')){
	 	 	 	 	 sords.push(fields[i].substr(pos + 1).trim() == 'desc' ? -1 : 1);
	 	 	 	 	 fields[i] = fields[i].substr(0, pos).trim();
	 	 	 	 }
	 	 	 	 else {
	 	 	 	 	 sords.push(postdata.sord == 'desc' ? -1 : 1);
	 	 	 	 	 fields[i] = fields[i].trim();
	 	 	 	 }
	 	 	 	 if(i > 0 && fields[i] == fields[i - 1]) fields[i] = undefined;
	 	 	 	 else {
	 	 	 	 	 var column = $thegrid.jqGrid('getColProp', fields[i]); //alert(JSON.stringify( column ));
	 	 	 	 	 if(column.sorttype == 'int')
	 	 	 	 	 	for(row in data) data[row][column.name] = parseInt(data[row][column.name]);
	 	 	 	 	 else if(column.sorttype == 'float')
	 	 	 	 	 	for(row in data) data[row][column.name] = parseFloat(data[row][column.name]);
	 	 	 	 }

	 	 	 }

	 	 	 data.sort(function(a, b) {
	 	 	 	 for(var i = 0; i < fields.length; ++i)
	 	 	 	 	 if(!fields[i]) continue;
	 	 	 	 	 else if(a[fields[i]] == b[fields[i]]) continue;
	 	 	 	 	 else if(a[fields[i]] > b[fields[i]]) return sords[i];
	 	 	 	 	 else return sords[i] * -1;
	 	 	 });
	 	 	 $thegrid[0].addJSONData(data);
	 	 },
	 	 error: function(jqXHR, textStatus, errorThrown){
	 	 	 alert("Erreur lors de la réception de données : " + textStatus);
	 	 }
	    });
	},
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