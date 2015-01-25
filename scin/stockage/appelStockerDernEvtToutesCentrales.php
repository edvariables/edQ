<?php

ini_set('display_errors', 0);

/*
 * Récupération des évènements des centrales
 */
include_once '../sites/stockageAbstract.php';
include_once '../sites/EDFevenements.php';
include_once '../dao/incident.php';
include_once '../dao/daoAbstract.php';
include_once '../dao/incidentDao.php';
include_once '../dao/incidentMapper.php';
include_once '../outils/rezo.php';
include_once '../outils/html.php';
include_once '../outils/simple_html_dom.php';

// préparation page html
$titre = 'EDF : Stocker derniers évênements des centrales';
Rezo::enteteFichierHtml($titre);

$ctrl = new EDFevenements();
$ctrl->miseAJourBaseIncidentEDF();
//echo Html::parHtmlBal('Affichage termine');

echo Html::finFichierHtml();
unset($ctrl);
?>

