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
</ul>