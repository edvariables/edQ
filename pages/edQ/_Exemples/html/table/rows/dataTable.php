<?php
if(isset($arguments) && isset($arguments['rows']))
	$rows = $arguments['rows'];
else if(isset($_REQUEST['rows']))
	$rows = $_REQUEST['rows'];
else {//demo
	$rows = array(
		array( 1, 'First', true )
		, array(2, 'Second', false )	
	);
	$columns = array('Id', 'Name', 'Enabled');
}
if(isset($arguments) && isset($arguments['columns']))
	$columns = $arguments['columns'];
else if(isset($_REQUEST['columns']))
	$columns = $_REQUEST['columns'];
else if(!isset($columns)) {
	$columns = array();
	if(isset($rows[0])){
		foreach($rows[0] as $id => $value){
			$columns[] = array(
				'id' => $id
				, 'type' => gettype($value)
			);
		}
	}
}
if(!is_array($columns)){
	$columns = array();
	if(isset($rows[0])){
		foreach($rows[0] as $id => $value){
			$columns[] = array( 'title' => $id );
		}
	}
}
else if(isAssociative($columns)){
	$columnsBase = $columns;
	if(!is_array($columnsBase['*']))
		$columnsBase['*'] = array( 'visible' => $columnsBase['*'] );
	$columns = array();
	if(isset($rows[0])){
		foreach($rows[0] as $id => $value){
			if(isset($columnsBase[$id]))
				$columnsBase[$id] = array_merge(array('title' => $id), $columnsBase['*'], $columnsBase[$id]);
			else
				$columnsBase[$id] = array_merge(array('title' => $id), $columnsBase['*']);
			
			$column = array_merge($columnsBase[$id], array( 'id' => $id ));
			if(!isset($column['type']))
				$column['type'] = gettype($value);
			$columns[] = $column;
			
		}
	}
}
else {
	$columnsBase = $columns;
	$columns = array();
	foreach($columnsBase as $value){
		if(is_array($value))
			$columns[] = $value;
		else
			$columns[] = array('title' => $value);
	}
}
var_dump($columns);
var_dump($rows);
//die();

$uid = uniqid('nodes');
?><table id="<?=$uid?>" cellpadding="0" cellspacing="0" border="0" 
		 class="display"></table>
<script>
$(document).ready(function() {
	$('#<?=$uid?>').dataTable( {
		"language": {
			"url": "jquery/dataTables/lang/dataTables.french.json"
		}
		, "aLengthMenu": [[10, 25, 50, 100, 500, -1], [10, 25, 50, 100, 500, "tous"]]
		, "iDisplayLength": 100
		/*, "order": [[ 0, "asc" ]]*/
		, "data" : <?= json_encode($files) ?>
		, "columns" : <?= json_encode($columns) ?>
    } );
} );
</script>