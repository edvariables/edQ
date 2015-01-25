<?php $node = node($node, __FILE__);
?>
<h3>Utiliser le buttonset pour un champ de type ButtonSet (402)</h3>

<h4>Téléchargement</h4>
<p style="white-space:pre;">
	<?php node(':zip', $node, 'call')?>
	
	
</p>

<h4>jquery/buttonset</h4>
<p style="white-space:pre;">
	déjà inclus dans le jquery.ui
</p>

<h4>Copie des fichiers ButtonSetDetail.tpl et ButtonSetEdit.tpl</h4>
<p style="white-space:pre;">
	\layouts\vlayout\modules\Vtiger\uitypes\ButtonSetDetail.tpl
	\layouts\vlayout\modules\Vtiger\uitypes\ButtonSetEdit.tpl

</p>

<h4>Copie du fichier uitypes\ButtonSet.php</h4>
<p style="white-space:pre;">
	\modules\Vtiger\uitypes\ButtonSet.php

</p>

<h4>Inclusion des .js et .css</h4>
<p style="white-space:pre;">
	Complément au css
	TODO : caser ça dans le skin
	<code>
		/* jQuery UI
		layouts\vlayout\skins\woodspice\style.css a aussi des modifs de couleur sur .ui-buttonset (marron au lieu du bleu du thème jQuery UI)
		*/
		/* affadit le texte des boutons non sélectionnés */
		.ui-buttonset .ui-button {
		  color: #5a5a5a;
		}
		.ui-buttonset .ui-button [class^="icon-"] {
		  opacity: 0.7;
		}
		.ui-buttonset .ui-state-active {
		  color: #ffffff;
		}
		.ui-buttonset .ui-state-active [class^="icon-"] {
		  opacity: 1;
		}</code>
</p>

<h4>Création du type 402 : color</h4>
<p style="white-space:pre;">
	<code>INSERT INTO `mg_vtigercrm2`.`vtiger_ws_fieldtype` (`uitype`, `fieldtype`)
VALUES ('402', 'buttonset');</code>

</p>

<h4>Modifier le champ uitype dans la table vtiger_field</h4>
<p style="white-space:pre;">
	<code>UPDATE `mg_vtigercrm2`.`vtiger_field` SET `uitype` = '402'
		WHERE `vtiger_field`.`fieldid` = ?????;</code>

</p>

<h4>Définir les textes des valeurs</h4>
<p style="white-space:pre;">
	Traductions :
		LBL_%FIELD_NAME%_TRUE
		LBL_%FIELD_NAME%_FALSE

</p>

<h4>Tester</h4>
<p style="white-space:pre;">
	Rappel : Attention au javascript dans smarty, ça peut boguer facilement avec des messages incompréhensibles :
	- pas de // mais /* ok */
	- des espaces autours des { } pour qu'il n'y ai pas de confusion avec {$SMARTY}
	
</p>


