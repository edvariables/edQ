<?php

ini_set('display_errors', 0);

/*
 * Stocker les dernières décisions ASN
 */
include_once '../sites/stockageAbstract.php';
include_once '../sites/ASNAbstract.php';
include_once '../sites/BaseAbstract.php';
include_once '../sites/ASNDecisions.php';
include_once '../sites/BaseDecision.php';
include_once '../sites/InstallationsPrioritaires.php';
include_once '../dao/decisionsASN.php';
include_once '../dao/daoAbstract.php';
include_once '../dao/decisionsASNDao.php';
include_once '../dao/decisionsASNMapper.php';
include_once '../outils/rezo.php';
include_once '../outils/html.php';
include_once '../outils/simple_html_dom.php';

// préparation page html
$titre = 'ASN : Stocker les dernières décisions';
Rezo::enteteFichierHtml($titre);

$ctrl = new ASNDecisions();
$ctrl->miseAJourBaseDecisionASN();
//echo Html::parHtmlBal('Affichage termine');

echo Html::finFichierHtml();
unset($ctrl);
?>

