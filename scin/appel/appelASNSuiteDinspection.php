<?php

include_once '../sites/stockageAbstract.php';
include_once '../sites/ASNAbstract.php';
include_once '../sites/ASNSuiteDinspection.php';
include_once '../sites/InstallationsPrioritaires.php';
include_once '../outils/rezo.php';
include_once '../outils/html.php';
include_once '../outils/simple_html_dom.php';

$titre = 'ASN : Lettres de suite d\'inspection des installations nucléaires triées par date';
Rezo::enteteFichierHtml($titre);

$avis = new ASNSuiteDinspection();
$avis->afficherListeDiverses();

echo Html::finFichierHtml();
//$avis->clear();
unset($avis);
?>


