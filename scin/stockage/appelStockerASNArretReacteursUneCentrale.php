<?php
ini_set('display_errors', 0);

include_once '../sites/InstallationsPrioritaires.php';
include_once '../sites/stockageAbstract.php';
include_once '../sites/ASNAbstract.php';
include_once '../sites/ASNArretReacteurs.php';
include_once '../outils/rezo.php';
include_once '../outils/html.php';

// 1) Initialisations
$titre = 'ASN : Stockage des arrêts de réacteurs';
Rezo::enteteFichierHtml($titre);
echo Html::parHtmlBal('Arrêts de la centrale', 'h2');

// préparation affichage liste des centrales
echo Html::formulaireHtmlDebut("Arrets", "stockerArretUneCentrale.php");
$nomSelection = "StkArrets";
$toutesCentrales = InstallationsPrioritaires::genereListeCentralesEnFonction();
$selectListe = Html::MettreTableauDansObjetSelect($toutesCentrales, $nomSelection);
echo Html::paragrapheHtml('Choisir la centrale : ') . $selectListe;
echo html::setSubmitForm("StkEnvoiArret", "Arrets de la centrale");
echo Html::formulaireHtmlFin();

// fin
echo Html::finFichierHtml();
unset($ctrl);
?>
