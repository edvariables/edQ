<?php
echo('<h3>Edition</h3>');
page::execute('Utilisateurs/Edition', $node);

echo('<h3>Préférences</h3>');
$args = array('return' => 'rows');
page::call(':Preferences/get', $args, $node);
unset($args['return']);
page::call('/_html/table/rows', $args);
?>