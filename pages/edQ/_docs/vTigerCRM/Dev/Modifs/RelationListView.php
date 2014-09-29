<h3>\modules\Vtiger\models\RelationListView.php</h3>
Evite d'exécuter une 2ème requête pour savoir si il y a plus d'enregistrements que dans la page
<pre><code>    
		$limitQuery = $query .' LIMIT '.$startIndex.','.($pageLimit+1); /* ED140907 + 1 instead of two db query */
		$result = $db-&gt;pquery($limitQuery, array());
		$relatedRecordList = array();
		
		$max_rows = min($db-&gt;num_rows($result), $pageLimit);/* ED140907 + 1 instead of two db query */
		for($i=0; $i < $max_rows; $i++ ) {
			$row = $db-&gt;fetch_row($result,$i);
			$newRow = array();
			foreach($row as $col=>$val){
			    if(array_key_exists($col,$relatedColumnFields)){
				$newRow[$relatedColumnFields[$col]] = $val;
			    }
			}
		
			//To show the value of "Assigned to"
			$newRow['assigned_user_id'] = $row['smownerid'];
			$record = Vtiger_Record_Model::getCleanInstance($relationModule->get('name'));
			$record-&gt;setData($newRow)->setModuleFromInstance($relationModule);
			$record-&gt;setId($row['crmid']);
			$relatedRecordList[$row['crmid']] = $record;
		}
		$pagingModel->calculatePageRange($relatedRecordList);

		/* ED140907 + 1 instead of two db query */
		$pagingModel-&gt;set('nextPageExists', $db->num_rows($result) &gt; $pageLimit);
		/*
		$nextLimitQuery = $query. ' LIMIT '.($startIndex+$pageLimit).' , 1';
		$nextPageLimitResult = $db-&gt;pquery($nextLimitQuery, array());
		if($db-&gt;num_rows($nextPageLimitResult) > 0){
			$pagingModel-&gt;set('nextPageExists', true);
		}else{
			$pagingModel-&gt;set('nextPageExists', false);
		}*/
</code></pre>
à faire aussi dans \modules\PriceBooks\models\RelationListView.php