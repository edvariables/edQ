<pre>
/Volumes/cogi4d/Sites/lamatrice/modules/Settings/Picklist/models/Module.php

public function renamePickListValues($pickListFieldName, $oldValue, $newValue, $moduleName) {
		$db = PearDatabase::getInstance();

		/* ED141205
		* récupère aussi la colonne primary key des tables contenant le champ
		*/
	       $query = '
		    SELECT vtiger_field.tablename, vtiger_field.columnname, vtiger_entityname.entityidcolumn
		    FROM vtiger_field
		    JOIN vtiger_entityname
			ON vtiger_entityname.tabid = vtiger_field.tabid
		    WHERE vtiger_field.fieldname = ?
		    AND vtiger_field.presence IN (0,2)
		';
		$result = $db->pquery($query, array($pickListFieldName));
		$num_rows = $db->num_rows($result);

		//As older look utf8 characters are pushed as html-entities,and in new utf8 characters are pushed to database
		//so we are checking for both the values
		$query = 'UPDATE ' . $this->getPickListTableName($pickListFieldName) . ' SET ' . $pickListFieldName . '=? WHERE ' . $pickListFieldName . '=? OR ' . $pickListFieldName . '=?';
		$db->pquery($query, array($newValue, $oldValue, Vtiger_Util_Helper::toSafeHTML($oldValue)));

		for ($i = 0; $i < $num_rows; $i++) {
			$row = $db->query_result_rowdata($result, $i);
			$tableName = $row['tablename'];
			$columnName = $row['columnname'];
			$entityidcolumn = $row['entityidcolumn'];
			$query = 'UPDATE ' . $tableName . ' SET ' . $columnName . '=? WHERE ' . $columnName . '=?';
			$db->pquery($query, array($newValue, $oldValue));
			
			/* ED141205
			 * traite aussi les éléments des picklist multiples
			 */
			$query = 'SELECT ' . $entityidcolumn . ' AS id, ' . $columnName . ' AS value FROM ' . $tableName
			    . ' WHERE ' . $columnName . ' LIKE CONCAT(\'% |##| \', ?)
				OR ' . $columnName . ' LIKE CONCAT(?, \' |##| %\')
			    ';
			$result2 = $db->pquery($query, array($oldValue, $oldValue));
			$num_rows2 = $db->num_rows($result2);
			for ($j = 0; $j < $num_rows2; $j++) {
			    $row = $db->raw_query_result_rowdata($result2, $j);
			    $multi_value = $row['value'];
			    
			    $row['value'] = preg_replace('/' . preg_quote(' |##| ' . $oldValue) . '$/', ' |##| ' . $newValue, $row['value']);
			    $row['value'] = preg_replace('/^' . preg_quote($oldValue . ' |##| ') . '/', $newValue . ' |##| ', $row['value']);
			    $row['value'] = preg_replace('/' . preg_quote(' |##| ' . $oldValue . ' |##| ') . '/', ' |##| ' . $newValue . ' |##| ', $row['value']);
			    
			    if($multi_value == $row['value'])
				print_r("Aucun changement dans $multi_value -> preg_quote = " . preg_quote(' |##| ' . $oldValue . ' |##| ') );
			    else {
				$query = 'UPDATE ' . $tableName . ' SET ' . $columnName . '=? WHERE ' . $entityidcolumn . '=?';
				$db->pquery($query, array($row['value'], $row['id']));
			    }
			}
		}

		$query = "UPDATE vtiger_field SET defaultvalue=? WHERE defaultvalue=? AND columnname=?";
		$db->pquery($query, array($newValue, $oldValue, $columnName));

		vimport('~~/include/utils/CommonUtils.php');

		$query = "UPDATE vtiger_picklist_dependency SET sourcevalue=? WHERE sourcevalue=? AND sourcefield=?";
		$db->pquery($query, array($newValue, $oldValue, $pickListFieldName));
				
		$em = new VTEventsManager($db);
		$data = array();
		$data['fieldname'] = $pickListFieldName;
		$data['oldvalue'] = $oldValue;
		$data['newvalue'] = $newValue;
		$data['module'] = $moduleName;
		$em->triggerEvent('vtiger.picklist.afterrename', $data);
		
		return true;
	}
</pre>