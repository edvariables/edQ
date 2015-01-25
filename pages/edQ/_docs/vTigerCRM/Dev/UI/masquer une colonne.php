<h3>Masquer la colonne "Assigné à" dans les ListView</h3>
//masquer partout (non testé, je ne l'ai fait qu'au cas par cas)
<code>UPDATE `rsdn_vtigercrm`.`vtiger_field` SET `summaryfield` = '0' WHERE `fieldname` LIKE 'assigned_user_id'</code>