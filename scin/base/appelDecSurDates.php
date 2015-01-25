<?php

include_once '../outils/rezo.php';
include_once '../outils/html.php';
include_once '../outils/simple_html_dom.php';

// 1) Initialisations
$titre = 'Liste des décisions ASN à une date donnée';
Rezo::enteteFichierHtml($titre);

// préparation affichage liste des decisions
echo Html::formulaireHtmlDebut("decisionsASN", "afficheDecSurDates.php");
echo Html::paragrapheHtml('Choisir les dates : ');
echo html::setTextForm('deDate', 'Depuis la date :', '01/01/2013'); // date('d/m/Y'));
echo html::setTextForm('aDate', 'jusqu\'à la date :', date('d/m/Y'));
echo html::setSubmitForm("envoiDecisionsDate", "Décisions ASN à ces dates");
echo Html::formulaireHtmlFin();

echo Html::finFichierHtml();
?>
