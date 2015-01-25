<?php
ini_set('display_errors', 0);

include_once '../sites/stockageAbstract.php';
include_once '../sites/EDFevenements.php';
include_once '../outils/rezo.php';
include_once '../outils/html.php';
include_once '../outils/simple_html_dom.php';

// 1) Initialisations
$titre = 'Liste des évènements d\'une installation';
Rezo::enteteFichierHtml($titre);

// préparation affichage liste des centrales
echo Html::formulaireHtmlDebut("centrales", "afficheEvtUneInstallation.php");
$nomSelection = "installation";
$ctrl = new EDFevenements();
$toutesCentrales = $ctrl->getListeToutesCentrales();
$selectListe = Html::MettreTableauDansObjetSelect($toutesCentrales, $nomSelection);
echo Html::paragrapheHtml('Choisir la centrale : ') . $selectListe;
echo html::setSubmitForm("envoiInstallation", "Evènements de la centrale");
echo Html::formulaireHtmlFin();

echo Html::finFichierHtml();
unset($ctrl);

?>
