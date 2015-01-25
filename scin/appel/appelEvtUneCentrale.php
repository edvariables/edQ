<?php
ini_set('display_errors', 1);
include_once '../sites/stockageAbstract.php';
include_once '../sites/EDFevenements.php';
include_once '../outils/rezo.php';
include_once '../outils/html.php';
include_once '../outils/simple_html_dom.php';

// 1) Initialisations
$titre = 'EDF : Liste des evenements d\'une centrale';
Rezo::enteteFichierHtml($titre);

// préparation affichage liste des centrales
echo Html::formulaireHtmlDebut("centrales", "afficheEvtUneCentrale.php");
$nomSelection = "Centrales";
$ctrl = new EDFevenements();
$toutesCentrales = $ctrl->getListeToutesCentrales();
$selectListe = Html::MettreTableauDansObjetSelect($toutesCentrales, $nomSelection);
echo Html::paragrapheHtml('Choisir la centrale : ') . $selectListe;
echo html::setSubmitForm("envoiCentrale", "Evènements de la centrale");
echo Html::formulaireHtmlFin();

echo Html::finFichierHtml();
unset($ctrl);

/*
 * Code généré :
 * 
 * <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
<head>
<title>Liste des evenements d'une centrale</title>
<meta http-equiv = "Content-Type" content = "text/html; charset=utf-8"/>
 </head >
<body><h1>Liste des evenements d'une centrale</h1><!--  Debut formulaire  -->
<form name="centrales" method="post"  action="">
0<select name='Centrales'>
    <option selected='selected' value='Belleville'>Belleville</option>
    <option value='Blayais'>Blayais</option>
    <option value='Bugey'>Bugey</option>
    <option value='Cattenom'>Cattenom</option>
    <option value='Chinon'>Chinon</option>
    <option value='Chooz'>Chooz</option>
    <option value='Civaux'>Civaux</option>
    <option value='Cruas'>Cruas</option>
    <option value='Dampierre'>Dampierre</option>
    <option value='Fessenheim'>Fessenheim</option>
    <option value='Flamanville'>Flamanville</option>
    <option value='Golfech'>Golfech</option>
    <option value='Gravelines'>Gravelines</option>
    <option value='Nogent-sur-Seine'>Nogent-sur-Seine</option>
    <option value='Paluel'>Paluel</option>
    <option value='Penly'>Penly</option>
    <option value='Saint-Alban'>Saint-Alban</option>
    <option value='Saint-Laurent-des-Eaux'>Saint-Laurent-des-Eaux</option>
    <option value='Tricastin'>Tricastin</option>
    <option value='Brennilis'>Brennilis</option>
    <option value='Creys-Malville'>Creys-Malville</option>
</select>
<p><input type="submit" value="Afficher" /></p>
</form>
<!--  Fin formulaire  -->
</body>
</html>
 * 
 */

?>
