<?php
	$args = array( 'title' => $node['nm'] );
	page::call(':subpage', $args);//prefered with , __FILE__

//echo ('node, sortie de call :');
//var_dump($node);

?>