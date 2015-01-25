<?php

ini_set('display_errors', 0);
/*
 * 
 */
include_once '../sites/stockageAbstract.php';
include_once '../sites/ASNAbstract.php';
include_once '../sites/BaseAbstract.php';
include_once '../sites/BaseDecision.php';
include_once '../dao/decisionsASN.php';
include_once '../dao/daoAbstract.php';
include_once '../dao/decisionsASNDao.php';
include_once '../dao/decisionsASNMapper.php';
include_once '../outils/rezo.php';
include_once '../outils/html.php';
include_once '../outils/simple_html_dom.php';

// préparation page html
$titre = 'Liste des décisions ASN d\'une installation';
$nePasAfficherH1 = false;
Rezo::enteteFichierHtml($titre, $nePasAfficherH1);

// 0) Traitement de la requete
if (isset($_POST['envoiDecInstallation'])) {
    if (isset($_POST['installation'])) {
        $installation = $_POST['installation'];
        $decisionsASN = new BaseDecision();
        $decisionsASN->decisionsDUneInstallation($installation);
        $decisionsASN->afficherListeDepuisBase();
    } else {
        echo paragrapheHtml('Choisissez l\'installation.');
    }
}
// fin
unset($decisionsASN);
echo Html::finFichierHtml();
?>
