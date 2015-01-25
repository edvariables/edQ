<?php
	$args = array( 'title' => $node['nm'] );
	$returned = page::call(':subpage', $args);//prefered with , __FILE__
	echo '<br><br>$returned :'; var_dump($returned);
//echo ('node, sortie de call :');
//var_dump($node);

?>