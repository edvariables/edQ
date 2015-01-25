<?php

//include_once 'sites/ASNAvisDincidents.php';
//include_once 'sites/ASNAvisDincidents.php';
//include_once 'sites/avis_asn.php';
include_once '../outils/rezo.php';
include_once '../outils/html.php';
include_once '../outils/simple_html_dom.php';
// préparation page html
$titre = 'Essai';
Rezo::enteteFichierHtml($titre);

/*
$avis = new Avis_asn();
//var_dump($avis);
$avis->recupererPageListe();
//$avis->afficherListeDiverses();
 * 
 */
$html = '<a href="/index.php/Les-actions-de-l-ASN/Le-controle/Actualites-du-controle/Avis-d-incidents-des-installations-nucleaires/2013/Detection-tardive-de-l-indisponibilite-partielle-du-circuit" class="title"> Détection tardive de l’indisponibilité partielle du circuit [...]</a>';
$lien = Html::urlDunLien($html);

var_dump($lien);

echo Html::finFichierHtml();

//unset($avis);

?>
