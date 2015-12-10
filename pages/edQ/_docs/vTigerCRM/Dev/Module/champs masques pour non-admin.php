<pre><code>
INSERT INTO `vtiger_def_org_field` (`tabid`, `fieldid`, `visible`, `readonly`)
SELECT tabid, fieldid, '0', '0' 
FROM vtiger_field WHERE fieldid
NOT IN (SELECT fieldid FROM vtiger_def_org_field);

/* ajout des éléments manquants dans vtiger_profile2field */
INSERT INTO `vtiger_profile2field` (`profileid`, `tabid`, fieldid, `visible`, `readonly`)
SELECT vtiger_profile.profileid, vtiger_field.tabid, vtiger_field.fieldid, '0', '0'
FROM vtiger_field
INNER JOIN vtiger_profile
	ON 1=1
LEFT JOIN vtiger_profile2field
	ON vtiger_profile2field.fieldid = vtiger_field.fieldid
    AND vtiger_profile.profileid = vtiger_profile2field.profileid
WHERE vtiger_profile2field.fieldid IS NULL;


/* brutalement */
UPDATE `vtiger_profile2field` SET `visible`=0 ;
</code></pre>
