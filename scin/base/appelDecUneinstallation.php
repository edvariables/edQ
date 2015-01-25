<?php

include_once '../sites/InstallationsPrioritaires.php';
include_once '../outils/rezo.php';
include_once '../outils/html.php';
include_once '../outils/simple_html_dom.php';

// 1) Initialisations
$titre = 'Liste des décisions ASN sur une installation';
Rezo::enteteFichierHtml($titre);

// préparation affichage liste des centrales
echo Html::formulaireHtmlDebut("Installations", "afficheDecUneInstallation.php");
$nomSelection = "installation";
$toutesInstallations = InstallationsPrioritaires::genereListeToutesInstallations();
$selectListe = Html::MettreTableauDansObjetSelect($toutesInstallations, $nomSelection);
echo Html::paragrapheHtml('Choisir l\'installation : ') . $selectListe;
echo html::setSubmitForm("envoiDecInstallation", "Décisions sur l'installation");
echo Html::formulaireHtmlFin();

echo Html::finFichierHtml();
unset($ctrl);

?>
