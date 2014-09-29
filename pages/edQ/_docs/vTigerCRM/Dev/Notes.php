<h2>vTigerCRM - <?=$node['nm']?></h2>
<ul class="edq-doc">
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