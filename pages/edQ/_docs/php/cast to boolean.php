<?php

echo '<li>$value = "false"';
$value = 'false';

echo '<li>$value';
	var_dump($value);

echo '<li>(bool)$value';
	var_dump((bool)$value);

echo '<li>filter_var($value, FILTER_VALIDATE_BOOLEAN)';
	var_dump(filter_var($value, FILTER_VALIDATE_BOOLEAN));
	
?>