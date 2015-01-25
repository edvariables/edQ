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
$titre = 'Liste des décisions ASN à une date donnée';
$nePasAfficherH1 = false;
Rezo::enteteFichierHtml($titre, $nePasAfficherH1);

// 0) Traitement de la requete

if (isset($_POST['envoiDecisionsDate'])) {
    if (isset($_POST['deDate'])) {
        $deDate = rezo::dateJMAversAMJstr($_POST['deDate']);

        if (isset($_POST['aDate'])) {
            $aDate = rezo::dateJMAversAMJstr($_POST['aDate']);
        }
        $decisionsASN = new BaseDecision();
        $decisionsASN->decisionsAUneDate($deDate, $aDate);
        $decisionsASN->afficherListeDepuisBase();
        unset($decisionsASN);
    } else {
        echo paragrapheHtml('Choisissez la date.');
    }
}
// fin
echo Html::finFichierHtml();
?>
