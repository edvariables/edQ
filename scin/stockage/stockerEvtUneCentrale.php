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
include_once '../sites/stockageAbstract.php';
include_once '../sites/EDFevenements.php';
include_once '../sites/ASNAbstract.php';
include_once '../sites/ASNAvisDincidents.php';
include_once '../sites/InstallationsPrioritaires.php';
include_once '../outils/rezo.php';
include_once '../outils/html.php';
include_once '../outils/simple_html_dom.php';

// préparation page html
$titre = 'EDF : Stockage des événements d\'une centrale';
Rezo::enteteFichierHtml($titre);

// Traitement de la requete
if (isset($_POST['stkCentrale'])) {
// test
    if (isset($_POST['ctrlAStocker'])) {
        $centrale = $_POST['ctrlAStocker'];
        echo html::parHtmlBal('Stockage en cours pour la centrale de ' . $centrale . ' …');
        $ctrl = new EDFevenements;
        $ctrl->tousEvtsUneCentrale($centrale);
        $nbrIncidents = $ctrl->StockerTousEvt1Centrale();
        echo html::parHtmlBal($nbrIncidents . ' incidents EDF stockés.');
        unset($ctrl);
        
    } else {
        echo html::parHtmlBal('Choisissez la centrale.');
    }
}
// fin
echo html::parHtmlBal('Traitement terminé.');
echo Html::finFichierHtml();
?>
