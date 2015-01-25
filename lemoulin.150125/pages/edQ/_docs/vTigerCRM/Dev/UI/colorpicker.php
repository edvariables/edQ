<?php $node = node($node, __FILE__);
?>
<h3>Utiliser le colorpicker pour un champ de type Color(401)</h3>

<h4>Téléchargement</h4>
<p style="white-space:pre;">
	<?php node(':zip', $node, 'call')?>
	
	
</p>

<h4>Copie des fichiers jquery/colorpicker</h4>
<p style="white-space:pre;">
	<a target="_blank" href="http://users.edvariables.net/edQ/res/jquery/colorpicker/">plugin jquery ColorPicker</a>
	copier depuis le zip jquery/colorpicker
	et coller dans le répertoire librairies/jquery/colorpicker du projet vTiger

		Modifier librairies/jquery/colorpicker/css/layout.css
entre autres :	z-index: 99999;

</p>

<h4>Copie des fichiers Color.tpl et ColorPicker.tpl</h4>
<p style="white-space:pre;">
	\layouts\vlayout\modules\Vtiger\uitypes\Color.tpl
	\layouts\vlayout\modules\Vtiger\uitypes\ColorPicker.tpl

</p>

<h4>Copie du fichier uitypes\Color.php</h4>
<p style="white-space:pre;">
	\modules\Vtiger\uitypes\Color.php

</p>

<h4>Inclusion des .js et .css</h4>
<p style="white-space:pre;">
	Peut être gérée dans les fonctions suivantes d'une extension du projet
		public function getHeaderScripts(Vtiger_Request $request) {}
		public function getHeaderCss(Vtiger_Request $request) {}
	
	Peut être gérée par la table vtiger_links
		
	Mais, au même titre que le datepicker, on peut l'inclure systématiquement dans :
	- \layouts\vlayout\modules\Vtiger\JSResources.tpl
	<code>{*ED141009*}
	&lt;script type="text/javascript" src="libraries/jquery/colorpicker/js/colorpicker.js"&gt;&lt;/script&gt;</code>
	- \layouts\vlayout\modules\Vtiger\Header.tpl
	<code>{*ED141009*}
	&lt;link rel="stylesheet" media="screen" type="text/css" href="libraries/jquery/colorpicker/css/colorpicker.css" /&gt;
	&lt;link rel="stylesheet" media="screen" type="text/css" href="libraries/jquery/colorpicker/css/layout.css" /&gt;</code>

</p>

<h4>Création du type 401 : color</h4>
<p style="white-space:pre;">
	<code>INSERT INTO `mg_vtigercrm2`.`vtiger_ws_fieldtype` (`uitype`, `fieldtype`)
VALUES ('401', 'color');</code>

</p>

<h4>Modifier le champ uitype dans la table vtiger_field</h4>
<p style="white-space:pre;">
	<code>UPDATE `mg_vtigercrm2`.`vtiger_field` SET `uitype` = '401'
		WHERE `vtiger_field`.`fieldid` = ?????;</code>

</p>

<h4>Tester</h4>
<p style="white-space:pre;">	
	TODO (pour manu)
	en DetailView \layouts\vlayout\modules\Vtiger\uitypes\ColorPicker.tpl
	- gerer le reset a la couleur d'origine (clic en haut a droite du pickcolor)
	- transposer le javascript dans un .js, genre vtiger_uitype_color
	- après enregistrement, affiche le #010fE24 de la couleur et le pickcolor ne fonctionne plus

	Rappel : Attention au javascript dans smarty, ça peut boguer facilement avec des messages incompréhensibles :
	- pas de // mais /* ok */
	- des espaces autours des { } pour qu'il n'y ai pas de confusion avec {$SMARTY}
	
</p>


