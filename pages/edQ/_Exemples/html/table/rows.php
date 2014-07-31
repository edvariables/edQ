<?php
if(isset($arguments) && isset($arguments['rows']))
	$rows = $arguments['rows'];
else if(isset($_REQUEST['rows']))
	$rows = $_REQUEST['rows'];
else //demo
	$rows = array(
		array('Id' => 1, 'Name' => 'First', 'Enable' => true )
		, array('Id' => 2, 'Name' => 'Second', 'Enable' => false )	
	);
if(isset($arguments) && isset($arguments['columns']))
	$columns = $arguments['columns'];
else if(isset($_REQUEST['columns']))
	$columns = $_REQUEST['columns'];
else {
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
if(isset($columns['*'])){
	$columnsBase = $columns;
	if(!is_array($columnsBase['*']))
		$columnsBase['*'] = array( 'visible' => $columnsBase['*'] );
	$columns = array();
	if(isset($rows[0])){
		foreach($rows[0] as $id => $value){
			if(isset($columnsBase[$id]))
				$columnsBase[$id] = array_merge($columnsBase['*'], $columnsBase[$id]);
			else
				$columnsBase[$id] = array_merge(array(), $columnsBase['*']);
			
			$column = array_merge($columnsBase[$id], array( 'id' => $id ));
			if(!isset($column['type']))
				$column['type'] = gettype($value);
			$columns[] = $column;
			
		}
	}
}

//var_dump($columns);
//die();

// index
$columnsIndex = array();
for($nCol = 0; $nCol < count($columns); $nCol++){
	$columns[$nCol]['index'] = $nCol;
	$columnsIndex[$columns[$nCol]['id']] = $nCol;
}
// index
$is_associative = count($rows) > 0 && isAssociative($rows[0]);
//var_dump($rows);
?>
<table class="edq">
<caption><?php
	//bouton de recherche
	/*?><input type="submit" value="Rechercher" style="margin-left: 2em;"/><?php*/
	//lien de téléchargement
	// source de donnees
	if(isset($arguments) && isset($arguments['csv--node']))
		$csv_node = $arguments['csv--node'];
	else if(isset($_REQUEST['csv--node']))
		$csv_node = $_REQUEST['csv--node'];
	else if(isset($node))
		$csv_node = $node['id'];
	else
		$csv_node = false;
	if($csv_node){
		// type de donnees a demander
		if(isset($arguments) && isset($arguments['csv--rows']))
			$csv_rows = $arguments['csv--rows'];
		else if(isset($_REQUEST['csv--rows']))
			$csv_rows = $_REQUEST['csv--rows'];
		else 
			$csv_rows = 'html';
		//nom du fichier
		if(isset($arguments) && isset($arguments['csv--file']))
			$csv_file = $arguments['csv--file'];
		else if(isset($_REQUEST['csv--file']))
			$csv_file = $_REQUEST['csv--file'];
		else if(isset($node))
			$csv_file = $node['nm'];
		else
			$csv_file = "donnees";

		$viewer = tree::get_id_by_name('/_Exemples/Convertisseurs/table/csv');
		$viewer_options = "&node=" . $csv_node
		. "&file--name=" . urlencode($csv_file)
		. "&node--get=" . $csv_rows;
		?><a class="file-download" href="view.php?id=<?=$viewer?><?=$viewer_options?>&vw=file.call" style="margin-left: 2em;">télécharger</a><?php
	}
?></caption>
<thead><tr><?php
	$nCol = 0;
	foreach($columns as $column)
		if(!isset($column['visible']) || $column['visible']){
			echo('<th>');
			echo htmlspecialchars(isset($column['text']) ? $column['text'] : $column['id'] );
			echo('</th>');
			++$nCol;
		}
?></tr></thead>
<tbody>
<?php
	foreach($rows as $row)
	if($row != null){
		echo('<tr>');
		$nCol = 0;
		
		foreach($columns as $column)
			if(!isset($column['visible']) || $column['visible']){
				echo('<td>');
				$cell = $row[$is_associative ? $column['id'] : $column['index']];
			
				switch(@$column['type']){
				case 'double':
				case 'float':
					if(is_string($cell))
						echo $cell;
					else
						echo number_format($cell, 3, ',', '');
					break;
				case 'bool':
				case 'boolean':
					if( $cell )
						echo '<span class="ui-icon ui-icon-check" title="ui-icon-check"> </span>';
					break;
				default:
					echo htmlspecialchars( $cell );
					break;
				}
				echo('</td>');
				++$nCol;
			}
		echo('</tr>');
	}
?>
</tbody>
</table>