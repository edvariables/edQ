<?php
ini_set('display_errors', 0);
include_once '../sites/stockageAbstract.php';
include_once '../sites/ASNAbstract.php';
include_once '../sites/ASNActualites.php';
include_once '../sites/InstallationsPrioritaires.php';
include_once '../outils/rezo.php';
include_once '../outils/html.php';
include_once '../outils/simple_html_dom.php';

$titre = 'ASN : Actualités';
Rezo::enteteFichierHtml($titre);

$avis = new ASNActualites();
$avis->afficherListeDiverses();

echo Html::finFichierHtml();
unset($avis);
?>
