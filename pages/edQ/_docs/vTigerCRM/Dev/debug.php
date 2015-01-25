https://wiki.vtiger.com/index.php/Developer_Debug_Techniques

<ul>
	<li><h4>Activer le debug des requêtes SQL</h4>
		Dans \include\database\PearDatabase.php, ligne ~806, function connect
		<pre>
$this->database = ADONewConnection($this->dbType);
<b>$this->database->debug = true; </b>
		</pre>
	</li>
	<li><h4>Activer le debug des requêtes SQL</h4>
		<pre>
$db = PearDatabase::getInstance();
$db->setDebug(true);
		</pre>
	</li>
	
	
	
	<li><h4>Ajouter la trace par echo_callstack()</h4>
		<pre>Fichier include\utils\utils.php
/**
 * echo call stack
 * ED141004
 */
function echo_callstack($skip = 1, $max = INF){
	echo('&lt;pre&gt;');
	$dt = debug_backtrace();
	foreach ($dt as $t)
		if($skip-- &gt; 0)
			continue;
		elseif ($max-- &lt; 1)
			break;
		else {
			echo $t['file'] . ' line ' . $t['line'] . ' function ' . $t['function'] . "()\n";
		}
	echo('&lt;/pre&gt;');
}
		</pre>
	</li>
</ul>