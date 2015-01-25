<?php
$args = array(
	"domain" => 'TEST'
	, "param" => "search"
	, "return" => 'value'
);
// lecture
page::call('/_System/Utilisateur/Preferences/get', $args);
echo "get : ";
var_dump($args);

// écriture
$args['value'] = '/' . TREE_ROOT_NAME . '/_System';;
page::call('/_System/Utilisateur/Preferences/set', $args);

// lecture
unset($args['value']);
page::call('/_System/Utilisateur/Preferences/get', $args);
var_dump($args);

// suppression
page::call('/_System/Utilisateur/Preferences/delete', $args);
var_dump($args);


?>