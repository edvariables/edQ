<?php
ini_set('display_errors', 0);
/*
 * 
 */
include_once '../sites/stockageAbstract.php';
include_once '../sites/ASNAbstract.php';
include_once '../sites/BaseAbstract.php';
include_once '../sites/BaseIncident.php';
include_once '../dao/incident.php';
include_once '../dao/daoAbstract.php';
include_once '../dao/incidentDao.php';
include_once '../dao/incidentMapper.php';
include_once '../outils/rezo.php';
include_once '../outils/html.php';
include_once '../outils/simple_html_dom.php';

// préparation page html
$titre = 'Liste des incidents à une date donnée';
$nePasAfficherH1 = false;
Rezo::enteteFichierHtml($titre, $nePasAfficherH1);

// 0) Traitement de la requete
$btestUneCentrale = false;
//var_dump($_POST);

if (isset($_POST['envoiInstallDate'])) {
    $btestUneCentrale = true;

// test
    if ($btestUneCentrale) {
        if (isset($_POST['deDate'])) {
            $deDate = rezo::dateJMAversAMJstr($_POST['deDate']);

            if (isset($_POST['aDate'])) {
                $aDate = rezo::dateJMAversAMJstr($_POST['aDate']);
            }
            $incidentBase = new BaseIncident();
            $incidentBase->incidentsAUneDate($deDate, $aDate);
            $incidentBase->afficherListeDepuisBase();
            unset($incidentBase);
        } else {
            echo paragrapheHtml('Choisissez la date.');
        }
    }
}
// fin
echo Html::finFichierHtml();
?>
