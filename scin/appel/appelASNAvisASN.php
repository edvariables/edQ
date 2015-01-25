<?php
ini_set('display_errors', 0);
error_reporting(E_WARNING & ~E_NOTICE & ~E_DEPRECATED); // PRODUCTION
ini_set('display_errors','on'); error_reporting(E_ALL & ~E_STRICT & ~E_WARNING & ~E_NOTICE & ~E_DEPRECATED);   // DEBUGGING

include_once '../sites/stockageAbstract.php';
include_once '../sites/ASNAbstract.php';
include_once '../sites/ASNAvisASN.php';
include_once '../sites/InstallationsPrioritaires.php';
include_once '../outils/rezo.php';
include_once '../outils/html.php';
include_once '../outils/simple_html_dom.php';

$titre = 'Avis de l\'ASN';
Rezo::enteteFichierHtml($titre);

$avis = new ASNAvisASN();
$afficherDetail = 1;
$NEPASAfficherDetail = 0;
$avis->afficherListeDiverses($afficherDetail);

echo Html::finFichierHtml();
unset($avis);
?>