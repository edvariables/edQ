<?php

include_once '../outils/rezo.php';
include_once '../outils/html.php';
include_once '../outils/simple_html_dom.php';

// 1) Initialisations
$titre = 'Liste des incidents à une date donnée';
Rezo::enteteFichierHtml($titre);

// préparation affichage liste des centrales
echo Html::formulaireHtmlDebut("centrales", "afficheEvtSurDates.php");
$nomSelection = "installDate";
echo Html::paragrapheHtml('Choisir les dates : ') . $selectListe;
echo html::setTextForm('deDate', 'Depuis la date :', date('d/m/Y'));
echo html::setTextForm('aDate', 'jusqu\'à la date :', date('d/m/Y'));
echo html::setSubmitForm("envoiInstallDate", "Evènements à ces dates");
echo Html::formulaireHtmlFin();

echo Html::finFichierHtml();
?>
