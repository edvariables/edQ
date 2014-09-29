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
	<li><h4>Nettoyage des tables de contacts</h4>
		<pre>
DELETE FROM `vtiger_contactaddress` WHERE `contactaddressid` IN
(SELECT `contactid` FROM `vtiger_contactscf` WHERE NOT cf_727 IS NULL AND cf_727 != 0);

DELETE FROM `vtiger_contactdetails` WHERE `contactid` NOT IN
(SELECT `contactaddressid` FROM `vtiger_contactaddress`);
		</pre>
	</li>
</ul>