<?php

ini_set('display_errors', 0);
/*
 * Stocker lesdécisions ASN
 */
include_once '../sites/stockageAbstract.php';
include_once '../dao/decisionsASN.php';
include_once '../dao/daoAbstract.php';
include_once '../dao/decisionsASNDao.php';
include_once '../dao/decisionsASNMapper.php';
include_once '../sites/ASNAbstract.php';
include_once '../sites/ASNDecisions.php';
include_once '../sites/InstallationsPrioritaires.php';
include_once '../outils/rezo.php';
include_once '../outils/html.php';
include_once '../outils/simple_html_dom.php';

// préparation page html
$titre = 'ASN : Stockage des décisions';
Rezo::enteteFichierHtml($titre);

echo html::parHtmlBal('=== Stockage en cours pour les décisions');
// récupération par tranche de 4 pages
$bPASDERecupPage = FALSE;
$viderTable = true;
$decision = new ASNDecisions($bPASDERecupPage);
$offset = 0; // 0->60 puis 80->120 puis 140->200
$decision->recupererTranche($offset);
$nbrDecision = $decision->StockerTousEvtDecision($viderTable);
unset($decision);
echo html::parHtmlBal($nbrDecision . ' décisions ASN stockés.');
// fin page html
echo html::parHtmlBal('Traitement terminé.');
echo Html::finFichierHtml();
?>
