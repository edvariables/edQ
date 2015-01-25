<?php

if(!isset($arguments)) {
	die (__FILE__ . " : procédure verrouillée");
	//page::call('critere', $arguments, $node);//adresse //critere_adr
	return;
}

$node = node($node);
include_once(page::file('helpers', $node));

$simulate = isset($arguments['simulate']) ? $arguments['simulate'] : true;
if($simulate){
	echo '<h3>Simulation</h3>';
	echo '<pre>$arguments = '; var_dump($arguments); echo '</pre>';
}

$file = $arguments['file'];

$table = $arguments['table'];
$charset = isset($arguments['charset']) ? $arguments['charset'] : 'ISO-8859-15';
$create_table = isset($arguments['create_table']) ? $arguments['create_table'] : FALSE;
$truncate_table = isset($arguments['truncate_table']) ? $arguments['truncate_table'] : NULL;
$columns = $arguments['columns'];
$max_rows = isset($arguments['max_rows']) && $arguments['max_rows'] != 0 ? $arguments['max_rows'] : INF;
$skip_rows = isset($arguments['skip_rows']) ? $arguments['skip_rows'] : 0;
$separator = false;

if($simulate && ($max_rows == INF || $max_rows > 20))
	$max_rows = 20;

$handle = fopen($file, "r");

$columns = get_columns($columns, $handle, $separator, $charset);
//echo '$columns : '; var_dump($columns);
//echo '$separator : '; var_dump($separator);
//echo 'get_sql_create_table : '; var_dump(get_sql_create_table($columns, $table));
//echo 'get_sql_insert_into : '; var_dump(get_sql_insert_into($columns, $table));

$db = get_db();
if($create_table){
	echo "table $table create<br/>";
	if($simulate)
		var_dump(get_sql_drop_table($table));
	else
		$db->query(get_sql_drop_table($table));
	if($simulate)
		var_dump(get_sql_create_table($columns, $table));
	else
		$db->query(get_sql_create_table($columns, $table));
}
//nbre de lignes ajoutees par requete
if(count($columns) === 0)
	$rows_counter_max = INF;
else
	$rows_counter_max = (int)max(1, min(512, $max_rows) * 8 / count($columns));


//sql data
$data = array();

$time_debut = time();

$sql_insert = get_sql_insert_into($columns, $table, $rows_counter_max);

if($skip_rows > 0)
	skip_file_rows($handle, $skip_rows);
else if(( $max_rows === INF || $truncate_table === TRUE)
  && !$create_table
  && $truncate_table !== FALSE) {
	echo "table $table truncated<br/>";
	if($simulate)
		var_dump(get_sql_truncate($table));
	else
		$db->query(get_sql_truncate($table));
}

$total_counter = 0;
$counter = 0;
while(($row = read_file_row($handle, $columns, $separator, $data, $charset)) !== FALSE){
	//if($counter ==0 )	var_dump( $data);
	if((++$counter) == $rows_counter_max){
		if($simulate)
			var_dump($sql_insert, $data);
		else
			$db->query($sql_insert, $data);
		$total_counter += $counter;
		$_GLOBALS['edQ--progress'] = $total_counter;
		$data = array();
		$counter = 0;
		if($total_counter >= $max_rows)
			break;
	}
	//var_dump( $row );
}
//les derniers
if($counter > 0){
	$sql_insert = get_sql_insert_into($columns, $table, $counter);
	if($simulate)
		var_dump($sql_insert, $data);
	else
		$db->query($sql_insert, $data);
		
	$total_counter += $counter;

	$data = false;
}

unset($_GLOBALS['edQ--progress']);

if( !feof($handle)
&&	$total_counter < $max_rows) {
	echo "Erreur: fgets() a échoué\n";
}

fclose($handle);

$time_elapsed = time() - $time_debut;
?><?=$total_counter?> en <?=$time_elapsed?> sec. <?php
if($time_elapsed > 0){
	?>(soit <?=round($total_counter / $time_elapsed * 10) / 10?> par sec.)<?php
	if($total_counter > 100){
		?>(soit <?=round( $time_elapsed / $total_counter * 1000 * 10) / 10 ?> sec. par millier)<?php
	}
}
?>