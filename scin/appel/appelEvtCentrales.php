<?php

/*
 * Récupération des évènements des centrales
 */

include_once '../sites/stockageAbstract.php';
include_once '../sites/EDFevenements.php';
include_once '../outils/rezo.php';
include_once '../outils/html.php';
include_once '../outils/simple_html_dom.php';

// préparation page html
$titre = 'EDF : Derniers évênements des centrales';
Rezo::enteteFichierHtml($titre);

// 1) Initialisations
$ctrl = new EDFevenements();

// 2) Recueillir et traiter les pages
//echo 'Debut traitement ';
//$dateRef = date('d-m-Y'); //2013-05-31';
$dateRef = '';

if ($dateRef != '') {
    echo paragrapheHtml('DateRef = ' . $dateRef);
}

if ($ctrl->dernierEvtCentrales($dateRef)) {
    // echo 'Traitement dernierEvtCentralesEnFonction OK';
} else {
    die('Le traitement s\'est interrompu sur une erreur');
}

$bDemanteles = true;
if ($ctrl->dernierEvtCentrales($dateRef, $bDemanteles)) {
    //echo 'Traitement dernierEvtCentralesDemanteles OK';
} else {
    die('Le traitement s\'est interrompu sur une erreur');
}

$ctrl->afficherEvenements();
echo paragrapheHtml('Affichage termine');

echo Html::finFichierHtml();
unset($ctrl);
?>

