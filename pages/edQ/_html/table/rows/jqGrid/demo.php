<?php
$sql = "SELECT * 
	FROM contact
	LIMIT 20";
$db = get_db('/_System/dataSource');

$arguments = array(
	'rows' => $db->all( $sql )
	, 'columns' => array(
		'*' => array(
			'editable' => true
		)
		, 'IdContact' => array(
			'editable' => false,
			'hidden' => true,
			'width' => 0,
			'key' => true
		)
		, 'IdContactRef' => array(
			'hidden' => true
		)
		, 'Name' => array(
			'width' => '300',
			label => 'Nom',
		)
		, 'ContactType' => array(
			label => 'Type',
		)
		, 'ShortName' => array(
			label => 'Initiales',
		)
		, 'Enabled' => array(
			label => 'Actif',
			width => 95,
			align => 'center',
			formatter => 'checkbox',
			edittype => 'checkbox',
			editoptions => array(
				value => 'Yes:No',
				defaultValue => 'Yes'
			)
		),
	)
);
/*
{name:'name',index:'name', width:100, editable:true, frozen : true},
{name:'invdate',index:'invdate', width:90, sorttype:"date", formatter:"date", frozen : true},
{name:'note1_sum_0',index:'note1_sum_0', width:80, align:"right",sorttype:"float", formatter:"number", editable:false},
{name: 'closed', index: 'closed', width: 95, align: 'center', formatter: 'checkbox', edittype: 'checkbox', editoptions: {value: 'Yes:No', defaultValue: 'Yes'}},
 */
page::call('/_html/table/rows/jqGrid', $arguments);
?>