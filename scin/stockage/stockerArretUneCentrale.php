<?php

ini_set('display_errors', 0);

include_once '../sites/InstallationsPrioritaires.php';
include_once '../sites/stockageAbstract.php';
include_once '../sites/ASNAbstract.php';
include_once '../sites/ASNArretReacteurs.php';
include_once '../dao/incident.php';
include_once '../dao/daoAbstract.php';
include_once '../dao/incidentDao.php';
include_once '../dao/incidentMapper.php';
include_once '../outils/rezo.php';
include_once '../outils/html.php';
include_once '../outils/simple_html_dom.php';

// Traitement de la requete
if (isset($_POST['StkEnvoiArret'])) {
    if (isset($_POST['StkArrets'])) {
        $centrale = $_POST['StkArrets'];

// préparation page html
        $titre = 'ASN : Arrêt de réacteurs - ' . $centrale;
        Rezo::enteteFichierHtml($titre);
        echo html::parHtmlBal('Stockage en cours pour la centrale de ' . $centrale . ' …');

// récupérer les arrets
        $arret = new ASNArretReacteurs();
        $arret->recupererPageListe($arret->getUrl(), $centrale);
        $nbrArrets = $arret->StockerTousEvt1Centrale();
        unset($arret);
        echo html::parHtmlBal($nbrArrets . ' arrêts de centrale stockés.');
        //
    } else {
        echo paragrapheHtml('Choisissez la centrale.');
    }
}
// fin
echo Html::finFichierHtml();
unset($arret);
?>
