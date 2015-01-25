<?php
ini_set('display_errors', 0);

include_once '../sites/stockageAbstract.php';
include_once '../sites/EDFevenements.php';
include_once '../outils/rezo.php';
include_once '../outils/html.php';
include_once '../outils/simple_html_dom.php';

// préparation page html
$titre = 'EDF : Liste des évènements d\'une centrale';
Rezo::enteteFichierHtml($titre);

// Traitement de la requete
if (isset($_POST['envoiCentrale'])) {
    $ctrl = new EDFevenements;

        if (isset($_POST['Centrales'])) {
            $centrale = $_POST['Centrales'];
            $ctrl->tousEvtsUneCentrale($centrale);
            $ctrl->afficherTousEvt1Centrale();
        } else {
            echo paragrapheHtml('Choisissez la centrale.');
        }    
}
// fin
echo Html::finFichierHtml();
?>
