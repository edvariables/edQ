<h2>vTigerCRM - <?=$node['nm']?></h2>
<ul class="edq-doc">

	<li><h3>[nouveau module] Notes</h3>
		<ul>
			<li>ModuleDesigner
			<ul>A la 1ère installation, il y a qq ratés.
				<li><pre>Il faut créer manuellement un enregistrement dans la table sql
<code>INSERT INTO `vtiger_tab` ( `tabid`, `name`, `presence`, `tabsequence`, `tablabel`
, `modifiedby`, `modifiedtime`, `customized`, `ownedby`, `isentitytype`, `version`, `parent` )
VALUES ( %MaxId+1%, '%ModuleName%', 1, -1, '%ModuleName%', NULL, NULL, 1, 0, 1, '1.0.0', '%ModuleName%' )
				</code></pre></li>
				<li><pre>Le fichier <code>languages\fr_fr\<var>%ModuleName%</var>.php</code> contient les traductions.
Après modification, réinjecter le fichier dans le zip.</pre></li>
				<li><pre>Si vous avez affecté le module à menu privé (nommé <var>%MenuName%s</var>), il faut ajouter la traduction dans ce même fichier
<code>languages\fr_fr\%ModuleName%.php</code> :
Ajoutez la ligne <code>'LBL_<var>%MenuName%</var>s' => 'Mes <var>%ModuleName%</var>s',</code>
Dans le fichier <code>layouts\vlayout\modules\Vtiger\MenuBar.tpl</code> se crée le panneau du menu Tous.
J'ai ajouté, ligne 58, <code>{* ED140822 : initialize $moduleName
*}{foreach key=moduleName item=moduleModel from=$moduleList}{break}{/foreach}
</code>
Attention, la mise à jour de vTiger écrasera cette modification !
<a href="https://discussions.vtiger.com/index.php?p=/discussion/172845/contribution-menubar-moremenus-vtranslatelbl_parent-modulename-bug"
>bug posté sur le forum</a></pre></li>
				<li><pre>Autre surprise, il manque l'enregistrement dans vtiger_entityname
<code>INSERT INTO `vtiger_entityname` (`tabid`, `modulename`, `tablename`, `fieldname`, `entityidfield`, `entityidcolumn`)
	VALUES ('51', 'RsnDons', 'vtiger_rsndon', 'montant', 'rsndonid', 'rsndonid')</code>
</pre></li>
				<li><pre>L'utilisation du "input mandatory" provoque le masquage des champs en édition.
Il faut modifier le champ displaytype de la table vtiger_field pour passer la valeur de 2 à 1.
</pre></li>
				</ul>
			<li><a href="http://community.vtiger.com/help/vtigercrm/developers/extensions/examples/uninstall-module.html">
				Supprimer un module</a></li>
			<li>field currency : voir edQ/_docs/vTigerCRM/Dev/Modifs/field currency
		</ul>
	</li>

</ul>