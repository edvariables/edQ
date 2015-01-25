<?php

ini_set('display_errors', 0);

/*
 * Stocker les dernières décisions ASN
 */
include_once '../sites/stockageAbstract.php';
include_once '../sites/ASNAbstract.php';
include_once '../sites/BaseAbstract.php';
include_once '../sites/ASNAvisDincidents.php';
include_once '../sites/BaseIncident.php';
include_once '../sites/InstallationsPrioritaires.php';
include_once '../dao/incident.php';
include_once '../dao/daoAbstract.php';
include_once '../dao/incidentDao.php';
include_once '../dao/incidentMapper.php';
include_once '../outils/rezo.php';
include_once '../outils/html.php';
include_once '../outils/simple_html_dom.php';

// préparation page html
$titre = 'ASN : Stocker les derniers événements ASN';
Rezo::enteteFichierHtml($titre);

$ctrl = new ASNAvisDincidents();
$ctrl->miseAJourBaseIncidentASN();
//echo Html::parHtmlBal('Affichage termine');

echo Html::finFichierHtml();
unset($ctrl);
?>

