<?php

ini_set('display_errors', 0);
include_once '../sites/stockageAbstract.php';
include_once '../sites/ASNAbstract.php';
include_once '../sites/ASNArretReacteurs.php';
include_once '../outils/rezo.php';
include_once '../outils/html.php';
include_once '../outils/simple_html_dom.php';

$bAfficherEnListe = TRUE;

// Traitement de la requete
if (isset($_POST['envoiArret'])) {
    if (isset($_POST['Arrets'])) {
        $centrale = $_POST['Arrets'];

// préparation page html
        $titre = 'ASN : Arrêt de réacteurs - ' . $centrale;
        Rezo::enteteFichierHtml($titre);

// récupérer les arrets
        $arret = new ASNArretReacteurs();
        $arret->recupererPageListe($arret->getUrl(), $centrale);

        if ($bAfficherEnListe) {
            $afficherDetail = 1;
            $nePasAfficherDetail = 0;
            $arret->afficherListeDiverses($afficherDetail);
        } else {
            $arret->afficherIncidents1Installation();
        }

        //
    } else {
        echo paragrapheHtml('Choisissez la centrale.');
    }
}
// fin
echo Html::finFichierHtml();
unset($arret);
?>
