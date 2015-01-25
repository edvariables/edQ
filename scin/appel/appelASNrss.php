<?php

/*
 * ASN : Flux RSS
 */

include_once '../sites/ASNrss.php';
include_once '../outils/rezo.php';
include_once '../outils/html.php';
include_once '../outils/simple_html_dom.php';

if (FALSE) {
    $titre = 'ASN : flux RSS';
    Rezo::enteteFichierHtml($titre);
}

$rss = new ASNrss();
$rss->recupereRss();
$rss->afficheRss();

//echo Html::finFichierHtml();
?>
