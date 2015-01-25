<?php

ini_set('display_errors', 0);
/*
 * Stocker les evts d'une centrale EDF
 */
include_once '../sites/stockageAbstract.php';
include_once '../dao/incident.php';
include_once '../dao/daoAbstract.php';
include_once '../dao/incidentDao.php';
include_once '../dao/incidentMapper.php';
include_once '../sites/EDFevenements.php';
include_once '../sites/ASNAbstract.php';
include_once '../sites/ASNAvisDincidents.php';
include_once '../sites/InstallationsPrioritaires.php';
include_once '../outils/rezo.php';
include_once '../outils/html.php';
include_once '../outils/simple_html_dom.php';

// préparation page html
$titre = 'EDF : Stockage des evenements des centrales';
Rezo::enteteFichierHtml($titre);

// Centrales une par une
if (false) {
    $toutesCentrales = InstallationsPrioritaires::genereListeCentralesEnFonction();

    foreach ($toutesCentrales as $centrale) {
        echo html::parHtmlBal('=== Stockage en cours pour la centrale de ' . $centrale . ' …');
        $ctrl = new EDFevenements;
        $ctrl->tousEvtsUneCentrale($centrale);
        $nbrIncidents = $ctrl->StockerTousEvt1Centrale();
        unset($ctrl);
        echo html::parHtmlBal($nbrIncidents . ' incidents EDF stockés.');
    }
}

// incidents ASN
if (false) {
    echo html::parHtmlBal('=== Stockage en cours pour les incidents');
    $avis = new ASNAvisDincidents();
    $nbrIncidents = $avis->StockerTousEvt1Centrale();
    unset($avis);
    echo html::parHtmlBal($nbrIncidents . ' incidents ASN stockés.');
}

// centrales EDF en démantelement
if (TRUE) {
    $bDemanteles = true;
    $centrale = 'Brennilis';
    echo html::parHtmlBal('=== Stockage en cours pour la centrale de ' . $centrale . ' …');
    $ctrl = new EDFevenements;
    $ctrl->tousEvtsUneCentrale($centrale, $bDemanteles);
    $nbrIncidents = $ctrl->StockerTousEvt1Centrale();
    unset($ctrl);
    echo html::parHtmlBal($nbrIncidents . ' incidents EDF stockés.');
    
    
    $centrale = 'Creys-Malville';
    echo html::parHtmlBal('=== Stockage en cours pour la centrale de ' . $centrale . ' …');
    $ctrl = new EDFevenements;
    $ctrl->tousEvtsUneCentrale($centrale, $bDemanteles);
    $nbrIncidents = $ctrl->StockerTousEvt1Centrale();
    unset($ctrl);
    echo html::parHtmlBal($nbrIncidents . ' incidents EDF stockés.');
}
// fin page html
echo html::parHtmlBal('Traitement terminé.');
echo Html::finFichierHtml();
?>
