<?php
echo "<ul>";
if(isset($arguments) && count($arguments)){
	 echo "<li><h2>appel de arguments.php avec $"."arguments</h2>";
	 echo '<li>';
	 var_dump($arguments);
}
else if(!isset($arguments)){
	$arguments = array();
	$arguments[ 'boucle' ] = 'bouclée';

	echo "<li><h1>appel de arguments.php sans arguments</h1>";
	echo '<li>' . call_page('arguments', $arguments, __FILE__);
}
else {
	echo "<li><h1>appel de arguments.php avec arguments vide</h1>";
	echo '<li>' . call_page('arguments', $arguments, __FILE__);
}
echo "</ul>";
?>