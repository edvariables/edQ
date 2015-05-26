<pre>
Attention : ce qui suit détruit toutes limitations de droits.
Les picklist afficheront tous les éléments à tous les utilisateurs.

! ATTENTION :
Une version php de la réparation des droits est disponible dans "Repair all"

la suite est un peu plus aléatoire...
!!

Réparation des droits des éléments de picklist.

Attention : ce qui suit détruit toutes limitations de droits.
Les picklist afficheront tous les éléments à tous les utilisateurs.

Bascule les roles sur H1 pour réattribue à tous les autres rôles

Insertion des manquants
<code>INSERT INTO `vtiger_role2picklist`(`roleid`, `picklistvalueid`, `picklistid`, `sortid`)  
SELECT 'H1', `picklistvalueid`, `picklistid`, `sortid`
FROM vtiger_role2picklist a
ON DUPLICATE KEY UPDATE vtiger_role2picklist.sortid = vtiger_role2picklist.sortid
</code>

Purge
<code>DELETE FROM `vtiger_role2picklist`
WHERE `roleid` != 'H1'
</code>

Recréation pour tous les rôles
<code>
INSERT INTO `vtiger_role2picklist`(`roleid`, `picklistvalueid`, `picklistid`, `sortid`)
SELECT vtiger_role.roleid, vtiger_role2picklist.`picklistvalueid`, vtiger_role2picklist.`picklistid`, vtiger_role2picklist.`sortid`
FROM `vtiger_role2picklist`
JOIN vtiger_role
	ON vtiger_role.roleid != vtiger_role2picklist.roleid
WHERE vtiger_role2picklist.roleid = 'H1'

</code>

Exemple : Ajoute les occurences manquantes de vtiger_accounttype
non testé
<code>
INSERT INTO `vtiger_role2picklist`(`roleid`, `picklistvalueid`, `picklistid`, `sortid`)
SELECT 'H1', `picklist_valueid`, 1, 999
FROM vtiger_accounttype
WHERE picklist_valueid NOT IN (
    SELECT picklistvalueid FROM `vtiger_role2picklist` WHERE `picklistid` = 1 AND roleid = 'H1'
)
</code>
	

</pre>