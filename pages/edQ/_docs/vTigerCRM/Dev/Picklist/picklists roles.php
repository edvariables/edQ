<pre>
Attention : ce qui suit détruit toutes limitations de droits.
Les picklist afficheront tous les éléments à tous les utilisateurs.

exécution sans contrôle.
</pre>

<?php

/* répare les rôles des éléments des picklists */
$node = node($node, __FILE__);

$db = get_db(); //nécessite un parent dataSource

//Liste des champs de picklists

$sql = "SELECT vtiger_picklist.picklistid, vtiger_field.fieldid, vtiger_field.tabid, vtiger_field.fieldname, vtiger_field.columnname, vtiger_field.uitype
	FROM vtiger_field
	JOIN vtiger_picklist
		ON vtiger_picklist.name = vtiger_field.fieldname
	WHERE vtiger_field.uitype IN (15,16)
	ORDER BY vtiger_field.fieldname
";
$picklists = $db->all($sql);

$sql = "SELECT MAX(picklistvalueid) as maxi
	FROM vtiger_role2picklist
";

$picklistvalueid = $db->one($sql) + 1;

$picklists_ok = array();
foreach($picklists as $picklist){
	$sql = 'SHOW COLUMNS IN vtiger_' . $picklist['fieldname'];
	//var_dump($sql);
	try {
		$columns = $db->all($sql);
		$picklist['picklist_table'] = 'vtiger_' . $picklist['fieldname'];
		$fields = '';
		foreach($columns as $column)
			$fields .= $column['Field'] . ', ';
		$picklist['picklist_valueid'] = strpos($fields, 'picklist_valueid') > 0;
		$picklist['columns'] = $fields;
		$picklist['newIds'] = '';
		
		if($picklist['picklist_valueid']){
			//Elements de la table sans picklist_valueid
			$sql = 'SELECT `'. $columns[0]['Field'] . '` AS FieldId, picklist_valueid
				FROM ' . $picklist['picklist_table'] .'
				WHERE picklist_valueid = 0 OR picklist_valueid IS NULL';
			$missings = $db->all($sql);
			
			//UPDATE
			//Attribution d'une valeur aux manquants
			$params = array();
			$sql = "UPDATE " . $picklist['picklist_table'] ."
				SET picklist_valueid = CASE `". $columns[0]['Field'] . "` 
			";
			foreach($missings as $missing){
				$sql .= " WHEN ? THEN ?";
				$params[] = $missing['FieldId'];
				$params[] = $picklistvalueid++;
			}
			$sql .= " ELSE `picklist_valueid` END";
			if($params){
				//$picklist['newIds'] = print_r($sql, true);
				
				$result = $db->query($sql, $params);
				$picklist['newIds'] = $result;	
			}
			

			//INSERT INTO vtiger_role2picklist
			// Création des droits pour le rôle H1
			$sql = "INSERT INTO `vtiger_role2picklist`(`roleid`, `picklistvalueid`, `picklistid`, `sortid`)  
					SELECT 'H1', `picklist_valueid`, ?, `sortorderid`
					FROM " . $picklist['picklist_table'] . "
					ON DUPLICATE KEY UPDATE vtiger_role2picklist.sortid = vtiger_role2picklist.sortid";
			$params = array($picklist['picklistid']);
			$result = $db->query($sql, $params);
			
		}
		
		$picklists_ok[] = $picklist;
	}
	catch(Exception $ex){
		$picklist['columns'] = $ex;
		$picklist['newIds'] = print_r($params, true);
		$picklists_ok[] = $picklist;
	}
}


$sql = "UPDATE `vtiger_picklistvalues_seq` SET `id`= 
					(SELECT MAX( `picklistvalueid`)
					FROM vtiger_role2picklist)";
$db->query($sql);

$sql = "INSERT INTO `vtiger_role2picklist`(`roleid`, `picklistvalueid`, `picklistid`, `sortid`)  
					SELECT 'H1', `picklistvalueid`, `picklistid`, `sortid`
					FROM vtiger_role2picklist a
			ON DUPLICATE KEY UPDATE vtiger_role2picklist.sortid = vtiger_role2picklist.sortid";
$db->query($sql);

//Purge
$sql = "DELETE FROM `vtiger_role2picklist`
	WHERE `roleid` != 'H1'";
$db->query($sql);

//Recréation pour tous les rôles
$sql = "INSERT INTO `vtiger_role2picklist`(`roleid`, `picklistvalueid`, `picklistid`, `sortid`)
SELECT vtiger_role.roleid, vtiger_role2picklist.`picklistvalueid`, vtiger_role2picklist.`picklistid`, vtiger_role2picklist.`sortid`
FROM `vtiger_role2picklist`
JOIN vtiger_role
	ON vtiger_role.roleid != vtiger_role2picklist.roleid
WHERE vtiger_role2picklist.roleid = 'H1'";
$db->query($sql);

node('/_html/table/rows', $node, 'call', array('rows' => $picklists_ok));

?>