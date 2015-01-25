<h3>Groupes signataires</h3>
<pre>

</pre>
<?php
$sql = "SELECT * 
FROM `adresse`
WHERE `signataire` = 1
";

$db = get_db();

page::call('/_format/csv/download link', array(
	'csv--node' => $node['id'],
	'csv--file' => $node['nm'],
));
$args = array( 'rows' => $db->all( $sql ) );
node('/_html/table/rows', $node, 'call', $args);