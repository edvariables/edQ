<?php
ini_set('display_errors', 1);
/*
 * Récupération des évènements des centrales
 */

include_once '../sites/stockageAbstract.php';
include_once '../sites/EDFevenements.php';
include_once '../sites/ASNAbstract.php';
include_once '../sites/BaseIncident.php';
include_once '../dao/incident.php';
include_once '../dao/incidentDao.php';
include_once '../dao/incidentMapper.php';
include_once '../outils/rezo.php';
include_once '../outils/html.php';
include_once '../outils/simple_html_dom.php';

// préparation page html
$titre = 'Test incidents installations';
Rezo::enteteFichierHtml($titre);

$incidentBase = new BaseIncident();
$incidentBase->alimenterInfos('');
$afficherDetail = 1;
echo 'affichage :';
$incidentBase->afficherListeDiverses($afficherDetail);

echo Html::finFichierHtml();
unset($incidentBase);
?>

