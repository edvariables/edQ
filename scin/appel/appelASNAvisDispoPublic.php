<?php
ini_set('display_errors', 0);
error_reporting(E_WARNING & ~E_NOTICE & ~E_DEPRECATED); // PRODUCTION
ini_set('display_errors','on'); error_reporting(E_ALL & ~E_STRICT & ~E_WARNING & ~E_NOTICE & ~E_DEPRECATED);   // DEBUGGING

include_once '../sites/stockageAbstract.php';
include_once '../sites/ASNAbstract.php';
include_once '../sites/ASNAvisDispoPublic.php';
include_once '../sites/InstallationsPrioritaires.php';
include_once '../outils/rezo.php';
include_once '../outils/html.php';
include_once '../outils/simple_html_dom.php';

$titre = 'Avis de mise à disposition du public organisée par les exploitants';
Rezo::enteteFichierHtml($titre);

$avis = new ASNAvisDispoPublic();
$afficherDetail = 1;
$NEPASAfficherDetail = 0;
$avis->afficherListeDiverses($afficherDetail);

echo Html::finFichierHtml();
unset($avis);
?>