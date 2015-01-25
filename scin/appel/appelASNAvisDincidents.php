<?php

include_once '../sites/stockageAbstract.php';
include_once '../sites/ASNAbstract.php';
include_once '../sites/ASNAvisDincidents.php';
include_once '../sites/InstallationsPrioritaires.php';
include_once '../outils/rezo.php';
include_once '../outils/html.php';
include_once '../outils/simple_html_dom.php';

$titre = 'ASN : Avis d\'incidents des installations nucléaires triés par date';
Rezo::enteteFichierHtml($titre);

$avis = new ASNAvisDincidents();
$afficherDetail = 1;
$NEPASAfficherDetail = 0;
$avis->afficherListeDiverses($afficherDetail);

echo Html::finFichierHtml();
unset($avis);
?>