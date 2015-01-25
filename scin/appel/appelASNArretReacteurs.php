<?php
ini_set('display_errors', 0);

include_once '../sites/stockageAbstract.php';
include_once '../sites/ASNAbstract.php';
include_once '../sites/ASNArretReacteurs.php';
include_once '../sites/InstallationsPrioritaires.php';
include_once '../outils/rezo.php';
include_once '../outils/html.php';
include_once '../outils/simple_html_dom.php';

// affiche le dernier arrêt de réacteur pour chaque centrale 
// (le titre demandé n'est pas très explicite)
$titre = 'ASN : Arrêts de réacteurs';
Rezo::enteteFichierHtml($titre);

$arret = new ASNArretReacteurs();
$arret->dernierArretCentrales();
$arret->afficherListeDiverses();

echo Html::finFichierHtml();
unset($arret);
?>

