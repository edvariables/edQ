<fieldset>
<legend><h1>Aperçu</h1></legend><?php
// Les données contiennent des balises php
if( strpos($_POST['data'], '<'.'?') >= 0 ){
	// TODO Check write and execution access
	
	// TODO Template	
	setlocale(LC_TIME, 'fra_fra');

	$path = $_SERVER['DOCUMENT_ROOT']
			. preg_replace('/(\/?(.+)\/\w+\.php$)?/', '$2', $_SERVER['PHP_SELF']);
	$file = str_replace('\\', '/', realpath($path)) . '~preview.tmp.php';
	
	file_put_contents(utf8_decode($file), $_POST['data']);
	
	include(utf8_decode($file));
	
	unlink($file);
}
// Html pure
else
	echo $_POST['data'];
?></fieldset>

<fieldset>
<legend><h1>source</h1></legend>
<pre><code><?=htmlentities($_POST['data'])?></code></pre>
</fieldset>