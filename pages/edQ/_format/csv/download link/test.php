<pre><?php
ob_start();
node('..', $node, 'call', array(
	'csv--node' => '/_Exemples/data/rows',
	'csv--rows' => 'rows',
) );
$html = ob_get_clean();
var_dump($html);
echo $html;

ob_start();
node('..', $node, 'call', array(
	'csv--node' => '/_Exemples/data/table',
	'csv--rows' => 'html',
) );
$html = ob_get_clean();
var_dump($html);
echo $html;
?></pre>