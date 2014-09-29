<?php

function get_field_value($str, $column)
{
    if(substr($str, 0, 1) == '"'
	  && substr($str, strlen($str) - 1, 1) == '"')
	  $str = str_replace('\\"', '"', substr($str, 1, strlen($str) - 2));

	switch($column['datatype']){
	case 'datetime': 
		return parse_date4d($str);
		break;
	case 'boolean': 
		return parse_boolean4d($str);
		break;
	case 'integer': 
		return parse_integer4d($str);
		break;
	case 'decimal': 
		return parse_decimal4d($str);
	default:
		return $str;
	}
}

function get_field_name($str, $charset='UTF-8')
{
    if(substr($str, 0, 1) == '"'
	  && substr($str, strlen($str) - 1, 1) == '"')
	  $str = str_replace('\\"', '"', substr($str, 1, strlen($str) - 2));

	$str = htmlentities($str, ENT_NOQUOTES, $charset);
    
	$str = preg_replace('#&([A-za-z])(?:acute|cedil|caron|circ|grave|orn|ring|slash|th|tilde|uml);#', '\1', $str);
    $str = preg_replace('#&([A-za-z]{2})(?:lig);#', '\1', $str); // pour les ligatures e.g. '&oelig;'
    $str = preg_replace('#&[^;]+;#', '', $str); // supprime les autres caractères
    
    return strtolower($str);
}

/* parse_date4d
*/
function parse_date4d ($input){
	$reg_datetime = '/^(?P<C>\d{2})?(?P<d>\d{2})[-\/](?P<m>\d{2})[-\/](?P<y>\d{2})(\s+(?P<h>\d{2}):(?P<i>\d{2}):(?P<s>\d{2}))?$/';
	$is_match = preg_match_all($reg_datetime, $input, $match);
	if($is_match){
		if($match['m'][0] == '00' || $match['d'][0] == '00')
			return null;
		return ( (isset($match['C']) && isset($match['C'][0]) ? $match['C'][0] : '20')
				  . $match['y'][0] . '-' . $match['m'][0] . '-' . $match['d'][0]);
	}
	if(substr($input, 0, 10) == '0000-00-00'
	|| substr($input, 0, 8) == '00/00/00'
	|| $input == 'NULL')
			return null;
	
	$reg_datetime = '/^(?P<h>\d{2}):(?P<i>\d{2}):(?P<s>\d{2})$/';
	$is_match = preg_match_all($reg_datetime, $input, $match);
	if($is_match){
		return ( '0000-00-00 ' . $match['h'][0] . ':' . $match['i'][0] . ':' . $match['s'][0]);
	}
		
	$reg_datetime = '/^(?P<y>\d{4})-(?P<m>\d{2})-(?P<d>\d{2})T(?P<h>\d{2}):(?P<i>\d{2}):(?P<s>\d{2})$/';
	$is_match = preg_match_all($reg_datetime, $input, $match);
	if($is_match){
		return ( $match['y'][0] . '-' . $match['m'][0] . '-' . $match['d'][0] . ' ' . $match['h'][0] . ':' . $match['i'][0] . ':' . $match['s'][0]);
	}
	return ($input);
}
	
/* parse_boolean4d
*/
function parse_boolean4d ($input){
	switch(strtolower($input)){
		case '':
			return null;
		case 'vrai' :
		case 'true':
		case '1':
			return 1;
		default:
			return 0;
	}
}
	
/* parse_integer4d
*/
function parse_integer4d ($input){
	if($input === '')
		return null;
	return (int)str_replace(',', '.', $input);
}

	
/* parse_decimal4d
*/
function parse_decimal4d ($input){
	if($input === '')
		return null;
	return (float)str_replace(',', '.', $input);
}


/* get_separator
*/
function get_separator($first_line){
	$pos_min = strlen($first_line);
	$sep_min = false;
	foreach(array("\t", ";", ",") as $sep){
		$pos = strpos($first_line, $sep);
		if($pos !== FALSE && $pos >= 0 && $pos < $pos_min){
			$pos_min = $pos;
			$sep_min = $sep;
		}
	}
	return $sep_min;
}

/* get_columns
*/
function get_columns(&$columns, &$handle, &$separator, $charset='ISO-8859-15'){
	$buffer = fgets($handle);
	if($buffer === FALSE) return $buffer;
	
	$separator = get_separator($buffer);
	
	if(is_array($columns))
		foreach($columns as $col_name => $column)
			$columns[get_field_name($col_name, $charset)] = $column;
	
	//echo('helpers get_columns : '); var_dump($columns);
	
	$rank = 0;
	$cols = array();
	
	foreach(explode($separator, preg_replace('/[\\r\\n]+$/', '', $buffer)) as $col_name){
		$field_name = get_field_name($col_name, $charset);
		if($field_name == '')
			break;
		if(isset($columns[$field_name])){
			$col = $columns[$field_name];
		}
		else {
			echo 'colonne inconnue : ' . $field_name . '<br>';
			$col = array();
		}
		$col['name'] = $col_name;
		if(!isset($col['field']))
			$col['field'] = $field_name;
		$col['rank'] = $rank++;
		$cols[] = $col;
	}
	//echo('helpers get_columns : '); var_dump($cols);
	return $cols;
}

/* read_file_row
*/
function read_file_row(&$handle, &$columns, &$separator, &$merge = null){
	$buffer = fgets($handle);
	if($buffer === FALSE) return $buffer;
	
	$row = preg_split('/(?!\\\\)' . $separator . '/', preg_replace('/[\\r\\n]+$/', '', $buffer)); 
	if($merge === null)
		$merge = $row;
	for($col = 0; $col < count($columns); $col++){
		if($columns[$col]['skip'] === TRUE){
			$merge[] = null;
			continue;
		}
		$merge[] = get_field_value($row[$col], $columns[$col]);
	}
	return $merge;
}

/* skip_file_rows
*/
function skip_file_rows(&$handle, $skip = 0){
	while($skip-- > 0)
		if(($buffer = fgets($handle)) === FALSE)
			break;
	return $skip;
}

/* get_sql_create_table
*/
function get_sql_create_table(&$columns, &$table_name){
	$sql = 'CREATE TABLE `' . $table_name . '` (';
		
	for($col = 0; $col < count($columns); $col++){
		if($col > 0) $sql .= ', ';
		 $sql .= '
		 `' . $columns[$col]['field'] . '`';
		switch($columns[$col]['datatype']){
		case 'datetime': 
			$sql .= ' DATETIME';
			break;
		case 'boolean': 
			$sql .= ' TINYINT(1)';
			break;
		case 'integer': 
			$sql .= ' INT(11) ';
			break;
		case 'text': 
			$sql .= ' TEXT ';
			break;
		default:
			$sql .= ' VARCHAR(255)';
			break;
		}
	}
	$sql .= ')';
	return $sql;
}

/* get_sql_drop_table
*/
function get_sql_drop_table(&$table_name){
	return 'DROP TABLE IF EXISTS `' . $table_name . '`';
}

/* get_sql_insert_into
*/
function get_sql_insert_into(&$columns, &$table_name, $rows_count = 1){
	$sql = 'INSERT INTO `' . $table_name . '` (';
	
	for($col = 0; $col < count($columns); $col++){
		if($col > 0) $sql .= ', ';
		 $sql .= '`' . $columns[$col]['field'] . '`';
	}
	$sql .= ')';
	
	$queries = '(';
	
	for($col = 0; $col < count($columns); $col++){
		if($col > 0) $queries .= ', ?';
		else $queries .= '?';
	}
	$queries .= ')';
	
	$sql .= '
	VALUES' . $queries ;
	
	if($rows_count > 1)
		$sql .= str_repeat(',' . $queries, $rows_count - 1) ;

	return $sql;
}

/* get_sql_truncate
*/
function get_sql_truncate(&$table_name){
	return 'TRUNCATE TABLE `' . $table_name . '`';
}
?>