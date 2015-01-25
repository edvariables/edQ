<?php
ini_set('display_errors', 1);

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
$titre = 'Liste des évènements d\'une installation';
$nePasAfficherH1 = false;
Rezo::enteteFichierHtml($titre, $nePasAfficherH1);

// 0) Traitement de la requete
if (isset($_POST['envoiInstallation'])) {
    //echo Html::paragrapheHtml('envoiInstallation');// debug
    $btestUneCentrale = true;

// test
    if ($btestUneCentrale) {
        if (isset($_POST['installation'])) {
            $installation = $_POST['installation'];
            $incidentBase = new BaseIncident();
   // echo Html::paragrapheHtml('BaseIncident');// debug
            $incidentBase->incidentsDUneInstallation($installation);
  //  echo Html::paragrapheHtml('incidentsDUneInstallation');// debug
            $incidentBase->afficherListeDepuisBase();
  //  echo Html::paragrapheHtml('afficherListeDepuisBase');// debug
        } else {
            echo Html::paragrapheHtml('Choisissez l\'installation.');
        }
    }
}
// fin
unset($incidentBase);
echo Html::finFichierHtml();
?>
