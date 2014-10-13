<h3><var>modules\ModuleDesigner\templates\6.0.0\ModuleName.php</var></h3>
<pre>Le défault est relatif aux picklists lorsque le champ a des propriétés fieldname et columnname différentes.
Le ModuleDesigner initialise le %ModuleName%.php avec les fonctions issues de ce fichier.
A l'installation, cette fonction va chercher la table contenant les valeurs des picklists.
La fonction construit le nom de la table d'après la propriété <var>columnname</var>
alors qu'il semblerait plus standard d'utiliser <var>fieldname</var>
Après correction de votre %ModuleName%.php, vous devez le réinjecter dans le .zip de votre génération de module par ModuleDesigner.
<code>
public static function deleteDuplicatesFromAllPickLists($moduleName)
	{
		global $adb,$log;

		$log->debug("Invoking deleteDuplicatesFromAllPickList(".$moduleName.") method ...START");

		$use_column = 'fieldname';//columnname ED141005
		
		//Deleting doubloons
		$query = "SELECT $use_column FROM `vtiger_field` WHERE uitype in (15,16,33) "
				. "and tabid in (select tabid from vtiger_tab where name = '$moduleName')";
		$result = $adb->pquery($query, array());

		$a_picklists = array();
		while($row = $adb->fetchByAssoc($result))
		{
			$a_picklists[] = $row[$use_column];
		}
		
		foreach ($a_picklists as $picklist)
		{
			static::deleteDuplicatesFromPickList($picklist);
		}
		
		$log->debug("Invoking deleteDuplicatesFromAllPickList(".$moduleName.") method ...DONE");
	}
</code>
</pre>