<?php

$args = array(
	"domain" => '_Pages/Recherche'
	, "param" => 'form'
	, "return" => 'value'
);
$args = page::call('/_System/Utilisateur/Preferences/get', $args);
var_dump($args);

?>