<h2>vTigerCRM - <?=$node['nm']?></h2>
<ul class="edq-doc">
	<li><h3>[help] wiki.vtiger.com </h3>
		<ul>
			<li><a href="http://community.vtiger.com/help/" target="_blank">
				community</a></li>
			<li><a href="http://community.vtiger.com/help/vtigercrm/developers/index.html" target="_blank">
				community #developers</a></li>
			<li><a href="https://wiki.vtiger.com/index.php/CodingGuidelines" target="_blank">
				CodingGuidelines</a></li>
			<li><a href="https://wiki.vtiger.com/index.php/CodingGuidelines#FutureUpgrades" target="_blank">
				CodingGuidelines #FutureUpgrades</a></li>
			<li><a href="http://vtiger.com/products/crm/docs/510/vtigerCRM_DataModel_5.2.1.pdf" target="_blank">
				CodingGuidelines #DataModel_5.2.1</a></li>
			<li><a href="https://wiki.vtiger.com/index.php/Developers#Coding_Guidelines" target="_blank">
				CodingGuidelines #Developers</a></li>
			<li><a href="https://wiki.vtiger.com/index.php/DevelopingModule" target="_blank">
				CodingGuidelines #DevelopingModule</a></li>
			<li><a href="http://forge.vtiger.com/frs/" target="_blank">
				forge.vtiger.com</a></li>
			
		</ul>
	</li>
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
				</ul>
			</li>
			<li><a href="http://community.vtiger.com/help/vtigercrm/developers/extensions/examples/uninstall-module.html">
				Supprimer un module</a></li>
		</ul>
	</li>
	<li><h3>[javascript] Faire des champs "Ville" une liste $.autocomplete</h3>
		<ul>
			<li><pre>class CityLookup dans modules/CityLookup
<code>protected function _registerLinks($moduleName)
	//ajoute un fichier javascript au chargement du site
	$thisModuleInstance->addLink("HEADERSCRIPT", "City Autofill", "modules/CityLookup/js/CityLookupAutofill.js");
</code></pre></li>
			<li><pre>modules/CityLookup/js/CityLookupAutofill.js
<code>$("#"+activeModule+"_editView_fieldName_"+fieldName).autocomplete({
	source: cities
});	</code>
On y modifie la liste des villes.
<code>var cities = [
	"New York", "Los Angeles", "Chevinay", "Houston", "Philadelphia",
	"Phoenix", "San Diego", "San Antonio", "Dallas", "Detroit", "Other"
]</code>
On y définit le nom des champs pris en compte.
<code>var fieldNames = ['mailingcity', 'othercity', 'city'];
var selector = '';
for(var i = 0; i &lt; fieldNames.length; i++)
	selector += ",#"+activeModule+"_editView_fieldName_"+fieldNames[i]
jQuery(selector.substr(1)).autocomplete({
	source: cities
});	</code></pre></li>
		</ul>
	</li>
	
	<li><h3>[importation] Echapper à la planification/cron au delà de 1000 lignes</h3>
		<ul>
			<li><pre>class <code>modules\Import\models\Config.php</code>
<code>'immediateImportLimit' => '99000',</code></pre></li>
		</ul>
	</li>
	
	<li><h3>[cron] Paramétrage</h3>
		<ul>
			<li><pre>Exécutez <code>cron\vtigercron.bat</code> pour exécuter la tâche</li>
			<li><pre>fichier <code>cron\intimateTaskStatus.bat</code>
<code>D:\Wamp\bin\php\php5.5.12\php.exe intimateTaskStatus.php</code></pre></li>
			<li><pre>fichier <code>modules\Calendar\SendReminder.bat</code>
<code>@echo off
cd ..\..\
set PHP_EXE="D:\Wamp\bin\php\php5.5.12\php.exe"
%PHP_EXE% SendReminder.php
</code></pre></li>
			<li><pre>fichier <code>cron\vtigercron.bat</code>
<code>set VTIGERCRM_ROOTDIR="D:\Wamp\www\mg.intranet"
set PHP_EXE="D:\Wamp\bin\php\php5.5.12\php.exe"</code></pre></li>
			<li><pre>fichier <code>cron\jobstartwindows.bat</code>
<code>schtasks /create /tn "vtigerCRM Notification Scheduler" /tr D:\Wamp\www\mg.intranet\cron\intimateTaskStatus.bat /sc daily /st 11:00:00 /RU SYSTEM
schtasks /create /tn "vtigerCRM Email Reminder" /tr D:\Wamp\www\mg.intranet\modules\Calendar\SendReminder.bat /sc minute /mo 1 /RU SYSTEM</code></pre></li>
		</ul>
	</li>
</ul>