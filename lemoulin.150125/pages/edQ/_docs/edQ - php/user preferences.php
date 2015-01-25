<h3>Mémoriser les préférences utilisateur dans un domaine</h3>
<pre><code>
$prefs = array(
	'root' => $arguments['f--root'],
	'content' => isset($arguments['f--content']) ? $arguments['f--content'] : false
);
$args['value'] = $prefs;
page::call('/_System/Utilisateur/Preferences/set', $args);
</code>

</pre>

<h3>Obtenir les préférences utilisateur dans un domaine</h3>
<pre><code>
$args = array(
	"domain" => '_Pages/Recherche'
	, "param" => 'form'
	, "return" => 'value'
);
$args = page::call('/_System/Utilisateur/Preferences/get', $args);
var_dump($args);
if($args){
	$prefs = json_decode($args['value'], true);
	var_dump($prefs);
}
</code>
<?php
$args = array(
	"domain" => '_Pages/Recherche'
	, "param" => 'form'
	, "return" => 'value'
);
$args = page::call('/_System/Utilisateur/Preferences/get', $args);
var_dump($args);
if($args){
	$prefs = json_decode($args['value'], true);
	var_dump($prefs);
}
?>
</pre>
