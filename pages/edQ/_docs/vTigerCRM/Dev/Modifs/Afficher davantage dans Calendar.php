<h2>vTigerCRM - <?=$node['nm']?></h2>
<ul class="edq-doc">
	<li><h4>Si l'on souhaite voir apparaitre plus de champ des activités/tâches dans la vue de l'agenda</h4></li>
	<li><h3><code>modules\Calendar\actions\Feed</code></h3>
	<ul><li><h3>à insérer dans function pullEvents(....)</h3>
		</li>
		<li><h4>ligne ~93 : après avoir généré une instance de QueryGenerator 
			et avant d'appeler $query = $queryGenerator->getQuery();</h4>
			<br>à la place de $queryGenerator->setFields(array('subject', ...
		<pre>
<code>	
//SG140908 ajout des 'cf_xxx','contact_id', 'activitytype' et 'parent_id' dans la liste de fields du querygenerator
//$basicfields est le tableau de champs utilisé dans la version originale auquel on a ajouté 'contact_id', 'activitytype' et 'parent_id'
//$customfields est la liste des Customfields du module Events
//$cfarray est un tableau associatif $customfieldname=>$customfieldlabel (par exemple cf_703=>Véhicules")
//Ces tableaux sont utilisés pour enrichir l'info sur les Events envoyée à fullcalendar.js
	$basicfields = array('subject', 'eventstatus', 'visibility','date_start','time_start','due_date','time_end','assigned_user_id','id','contact_id', 'activitytype','parent_id');
	$finalfields = array();
	$customfields = array();
	$cfarray = array();
	$this->fillCustomFieldsArrays($customfields,$cfarray,'Events');
		
	$finalfields = array_merge($customfields,$basicfields);	
				
	$queryGenerator->setFields($finalfields);
</code></pre></li>
<li><h4>dans la boucle "while($record = $db->fetchByAssoc($queryResult)) {"</h4>			
<pre><code>		
// SG140904 Ajout info evenement : item.cflbls = array associatif cfname=>cflbl (construit par fillCustomFieldsArrays()) 
// pour chaque custom field, item.cfname = valeur du custom field 
	$item['cflbls'] = $cfarray;
		foreach ($cfarray as $cfnm=>$cflbl) {				
			$item[$cfnm] = $record[$cfnm];
		}
			
	if ($record['contactid']) {$item['contactname'] = decode_html(getContactName($record['contactid']));}
//record['crmid'] est l'id de l'entité liée à l'évènement issue de la table vtiger_seactivityrel. Ne pas confondre avec $crmid qui est l'id de cet event.
	if ($record['crmid']) {
		$pt = getSalesEntityType($record['crmid']);		
		$item['parenttype'] = vtranslate('SINGLE_'.$pt,$pt);				
		if (getCampaignName($record['crmid']) && getCampaignName($record['crmid'])!='') {
					$item['parentname'] = getCampaignName($record['crmid']);
							}
		if (getPotentialName($record['crmid']) && getPotentialName($record['crmid'])!='') {
					$item['parentname'] = getPotentialName($record['crmid']);
							}
		if (getAccountName($record['crmid']) && getAccountName($record['crmid'])!='') {
					$item['parentname'] = getAccountName($record['crmid']);
						}
						}
		if ($record['activitytype']) $item['activitytype'] = vtranslate($record['activitytype'],'Calendar');
// END of SG1409	
		</code>	</pre>
			</li>
		<li><h4>après cette boucle, lorsque $result est rempli, il faut traiter $result avec un groupbyid "maison" 
			afin que la même activité n'apparaisse pas deux fois (dans le cas de contacts multiples ou autres)" :</h4>
			<pre><code>	
			$this->groupResultsById($result);
			</code>	</pre></li>
		</ul>
	</li>
	
	<ul><li><h3>à insérer dans function pullTasks(....)</h3>
		</li>
		<li><h4>après avoir généré une instance de QueryGenerator 
			et avant d'appeler $query = $queryGenerator->getQuery();</h4>
			<br>à la place de $queryGenerator->setFields(array('subject', ...
		<pre>
<code>	
//SG140908 ajout des 'cf_xxx','contact_id', 'activitytype' et 'parent_id' dans la liste de fields du querygenerator
//$basicfields est le tableau de champs utilisé dans la version originale auquel on a ajouté 'contact_id', 'activitytype' et 'parent_id'
//$customfields est la liste des Customfields des evenements considérés
//$cfarray est un tableau associatif $customfieldname=>$customfieldlabel (par exemple cf_703=>Véhicules")
//Ces tableaux sont utilisés pour enrichir l'info sur les Events ou les Tasks envoyée à fullcalendar.js
	$basicfields = array('subject', 'taskstatus','date_start','time_start','due_date','time_end','assigned_user_id','id','contact_id', 'activitytype','parent_id');
	$finalfields = array();
	$customfields = array();
	$cfarray = array();
	$this->fillCustomFieldsArrays($customfields,$cfarray,'Tasks');
	$finalfields = array_merge($customfields,$basicfields);			
	$queryGenerator->setFields($finalfields);
// END
</code></pre></li>
		
	<li><h4>dans la boucle "while($record = $db->fetchByAssoc($queryResult)) {"</h4>			
<pre><code>		
// SG140904 Ajout info evenement : item.cflbls = array associatif cfname=>cflbl (construit par fillCustomFieldsArrays()) 
// pour chaque custom field, item.cfname = valeur du custom field 
	$item['cflbls'] = $cfarray;
		foreach ($cfarray as $cfnm=>$cflbl) {				
			$item[$cfnm] = $record[$cfnm];
		}
			
	if ($record['contactid']) {$item['contactname'] = decode_html(getContactName($record['contactid']));}
//record['crmid'] est l'id de l'entité liée à l'évènement issue de la table vtiger_seactivityrel. Ne pas confondre avec $crmid qui est l'id de cet event.
	if ($record['crmid']) {
		$pt = getSalesEntityType($record['crmid']);		
		$item['parenttype'] = vtranslate('SINGLE_'.$pt,$pt);				
		if (getCampaignName($record['crmid']) && getCampaignName($record['crmid'])!='') {
					$item['parentname'] = getCampaignName($record['crmid']);
							}
		if (getPotentialName($record['crmid']) && getPotentialName($record['crmid'])!='') {
					$item['parentname'] = getPotentialName($record['crmid']);
							}
		if (getAccountName($record['crmid']) && getAccountName($record['crmid'])!='') {
					$item['parentname'] = getAccountName($record['crmid']);
						}
						}
// END of SG1409	
		</code>	</pre>
			</li>
		<li><h4>Après cette boucle, lorsque $result est rempli, il faut traiter $result comme dans pullEvents  :</h4>
			<pre><code>	
			$this->groupResultsById($result);
			</code>	</pre></li>	
		</ul>
<ul>
<li><h3>Les modifications précédentes font appel à deux nouvelle functions à insérer : fillCustomFieldsArrays et groupResultsById</h3>
	<pre><code>	
	protected function fillCustomFieldsArrays(&$customfields,&$cfarray,$eventtype) {
		//$customfields = array();
		//$cfarray = array();
		switch ($eventtype) {
			case 'Events' :
				$eventfields = getColumnFields('Events');
				$tabid = getTabid('Events');
				break;
			case 'Tasks' :
				$eventfields = getColumnFields('Calendar');
				$tabid = getTabid('Calendar');
				break;
			default :
				
				$eventfields = array();
		}	
		foreach ($eventfields as $fldnm=>$v) {			
			if (strpos ($fldnm,'cf_')!==false && strpos ($fldnm,'cf_') === 0) {
				array_push($customfields,$fldnm);	
					$cfid = getFieldid($tabid,$fldnm);
					$cfrealtabid = getSingleFieldValue('vtiger_field','tabid','fieldid',$cfid);
					if ($cfrealtabid==$tabid) {
						$cflbl = getSingleFieldValue('vtiger_field','fieldlabel','fieldid',$cfid);
						$cfarray[$fldnm]=$cflbl;
					}
				}		
			}
	}	
	</code>	</pre>
	<pre><code>	
	// Because a same activity can have multiple contacts or other field values, the SQL response gives multiple rows for the same id
	// This function acts as a basic "concat_group () ... GROUP BY id", concatenating the fields which have multiple values.
	// The function works good here because the rows are quite similar and differ only for few fields. It doesn't pretend to be an universal "group by".
	// This couldn't be done by SQL without modifying the QueryGenerator, which might have caused other issues...
	//@param String: Result rows given by SQL
	//@return Array : Grouped and concatenated rows.
	
	protected function groupResultsById(&$res) {
	    $keychanged = array();
	    $resulttodel = array();
	    $changemap = array();
	    foreach ($res as $i=>$activity) {   
		$activityid = $activity['id'];
		if ($i < count($res)-1) {
		    for ($k=$i+1; $k<count($res);$k++) {          
			if ($activityid == $res[$k]['id']) {
			    if (is_array($resulttodel)&& in_array($k,$resulttodel)) {}
				else {
				    $diff = array_diff_assoc($activity,$res[$k]);
				    foreach ($diff as $key=>$value) {                         
				      $resulttodel[] = $k;                                               
				      $changemap[$i][$key][] = $res[$k][$key];          
				    }
				}
			    }       
		    }   
		}
	    };
	    foreach ($changemap as $rsltindex=>$keystochange) {
		foreach ($keystochange as $key=>$valuestoadd) {
		    $changemap[$rsltindex][$key] = array_unique($changemap[$rsltindex][$key]);
		}
	    }
		// on agrège les champs à valeur distinctes (cela suppose que ces valeurs soient des strings)
	    foreach ($changemap as $rsltindex=>$keystochange) {    
	       foreach ($keystochange as $key=>$valuestoadd) {     
		    foreach ($valuestoadd as $v)
			$res[$rsltindex][$key] .= ', '.$v;  
		}  
	    }
		// on enlève les pseudos doublons
	    for ($j=count($res)-1;$j>0;$j--) {
		if (is_array($resulttodel) && in_array($j,$resulttodel)) {       
		    unset($res[$j]);      
		}
	    };
		// on réindexe tout de 0 à n
	    foreach ($res as $value){
		$finalresult[] = $value;
		}
	    $res = $finalresult;
	}
	</code>	</pre>
			</li>	
		</ul>
	
	
	<li><h3><code>libraries\fullcalendar\fullcalendar.js</code></h3>
		<ul><li><h4>affichage des nouvelles données extraites par les modifs précédentes</h4>
			<h4>dans la function : slotSegHtml(event, seg)</h4>
				<pre><code>//SG140908 création description d'evenement
		event.description = "";
		if (event.cflbls !== undefined) {		
			for (cfname in event.cflbls) {
				if (event[cfname] != null && event[cfname] != '') {
					event.description += "&ltdiv class='event-description-customfield "+ cfname +"'&gt" + event.cflbls[cfname] + " : " + event[cfname].split("|##|") + "&lt/div&gt";
						}				
				}
		}
		if (event.activitytype && event.activitytype != '') event.description += "&ltdiv class='event-description-activitytype'>" + event.activitytype + "&lt/div&gt";
		
		if (event.parenttype && event.parenttype != '') {
			event.description += "&ltdiv class='event-description-parent'>" + htmlEscape(event.parenttype) + " : " + htmlEscape(event.parentname) + "&lt/div&gt";
		}
		if (event.contactname && event.contactname != '') {
			event.description += "&ltdiv class='event-description-contact'> Contact : " + htmlEscape(event.contactname) + "&lt/div&gt";
		}
		//END
		
</code></pre></li>
</ul>
		<ul><li><h4>affichage de event.description  : ajout dans la variable html, après le 'div' de class 'fc-event-title'</h4>
		<pre><code>
		/* ED140902 */
			"&ltdiv class='fc-event-description'&gt" +
			event.description +
			"&lt/div&gt" +
		</code></pre></li>
		</ul>
</li>
	
		<ul><li><h4>même modif dans daySegHTML(segs), dans la boucle sur les segs[i] </h4>
			<pre><code>
				//SG1409 ajout description
			event.description = "";
			if (event.activitytype && event.activitytype!='') event.description += "&ltdiv class='event-description-activitytype'>" + event.activitytype + "&lt/div&gt";
			//Affichage des custom fields
			if (event.cflbls !== undefined) {		
			for (cfname in event.cflbls) {
				if (event[cfname] != null && event[cfname] != '') {
					event.description += "&ltdiv class='event-description-customfield "+ cfname +"'&gt" + event.cflbls[cfname] + " : " + event[cfname].split("|##|") + "&lt/div&gt";
						}				
				}
			}
			if (event.parenttype && event.parenttype != '') {
				event.description += "&ltdiv class='event-description-parent'&gt" + htmlEscape(event.parenttype)+ " : " + htmlEscape(event.parentname) + "&lt/div>"
				}
			if (event.contactname && event.contactname != '') {
				event.description += "&ltdiv class='event-description-contact'&gt Contact : " + htmlEscape(event.contactname) + "&lt/div&gt"
				}	
			//END
				
	</code></pre></li>
			<li><h4>et ajouter "event.description" dans la variable "html" après le span de classe "fc-event-title" </h4>
</ul>