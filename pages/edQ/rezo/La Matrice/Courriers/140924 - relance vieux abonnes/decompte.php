<div class="stats-0">
<pre><h3>Abonnés il y a trois ans et qui ne nous ont plus contactés </h3>
	Abonné signifie des dates d'abonnements d'au moins un an d'écart.
	Le critère Abo_payé n'est pas satisfaisant car ne couvre pas tous ceux qui ont des dates d'abonnements définies.
</pre>
<?php
$date_abo = '2010-09-01';
$fin_abo = '2011-09-01';
$fin_rev = '2012-03-01';

if(false) {
	?>
<pre>
<h4>Vieux abonnés : fin d'abonnement (champs abn_rezo) avant le <?=$fin_abo?></h4>
avec un écart d'un an mini entre le début et la fin de l'abonnement
<?php
$sql = "SELECT COUNT(*) AS Nbre
FROM adresse
WHERE date_finabn_rezo BETWEEN '1900-01-01' AND '$fin_abo'
AND DATEDIFF(date_finabn_rezo, date_abn_rezo) > 360
AND pasrelanceabo = 0
AND codepostal <> ''
AND statutnpai <= 2";

$db = get_db();
?>---> Résultat : <b><?= $db->all( $sql )[0] ?></b>
</pre>

	
<pre>
<h4>Vieux abonnés : fin d'abonnement (champs abn_rezo) avant le <?=$fin_rev?></h4>
avec un écart d'un an mini entre le début et la fin de l'abonnement
A reçu la relance A13
<?php
$sql = "SELECT COUNT(*) AS Nbre
FROM adresse
WHERE reffiche IN (SELECT reffiche
	FROM critere_adr
	WHERE critere = 'Relance A13 12/2013'
)
AND pasrelanceabo = 0
AND codepostal <> ''
AND statutnpai <= 2";

$db = get_db();
?>---> Résultat : <b><?= $db->all( $sql )[0] ?></b>
</pre>

	<? }?>
<pre>
<h4>Vieux abonnés : fin d'abonnement (champs abn_rezo) avant le <?=$fin_rev?></h4>
avec un écart d'un an mini entre le début et la fin de l'abonnement
Pas de lignefacturecogilog après <?=$fin_abo?>
<?php
$sql = "SELECT COUNT(*) AS Nbre
FROM adresse
WHERE date_finabn_rezo BETWEEN '1900-01-01' AND '$fin_abo'
AND DATEDIFF(date_finabn_rezo, date_abn_rezo) > 360
AND reffiche NOT IN (
    SELECT `reffiche`
	FROM lignefacturecogilog
	WHERE datepiece >= '$fin_rev'
) 
AND pasrelanceabo = 0
AND codepostal <> ''
AND statutnpai = 0";

$db = get_db();
?>---> Résultat : <b><?= $db->all( $sql )[0] ?></b>
</pre>
	
	
<pre>
<h4>Vieux abonnés : fin d'abonnement (champs abn_rezo) avant le <?=$fin_rev?></h4>
avec un écart d'un an mini entre le début et la fin de l'abonnement
Pas de lignefacturecogilog après <?=$fin_abo?>,
Qui n'ont pas reçu l'appel à dons AD2 à AD4
NPAI = 0
<?php
$sql = "SELECT COUNT(*) AS Nbre
FROM adresse
WHERE date_finabn_rezo BETWEEN '1900-01-01' AND '$fin_abo'
AND DATEDIFF(date_finabn_rezo, date_abn_rezo) > 360
AND reffiche NOT IN (
    SELECT `reffiche`
	FROM lignefacturecogilog
	WHERE datepiece >= '$fin_rev'
) 
AND reffiche NOT IN (
    SELECT `reffiche`
	FROM critere_adr
	WHERE critere LIKE 'relanceF_%_AD2'
	OR critere LIKE 'relanceF_%_AD3'
	OR critere LIKE 'relanceF_%_AD4'
) 

AND pasrelanceabo = 0
AND codepostal <> ''
AND statutnpai = 0";

$db = get_db();
?>---> Résultat : <b><?= $db->all( $sql )[0] ?></b>
</pre>
	
	
<?php if(false){?>
<pre>
<h4>Vieux abonnés : date du dernier Abo_paye avant le <?=$date_abo?> (donc, fin d'abo le <?=$fin_abo?>)</h4>
<?php
$sql = "SELECT COUNT(*) AS Nbre
FROM adresse
WHERE reffiche IN (
    SELECT `reffiche`
	FROM (SELECT `reffiche`, MAX(dateapplication)
		FROM critere_adr
		WHERE critere = 'Abo_payé'
		GROUP BY `reffiche`
		HAVING MAX(dateapplication) < '$date_abo'
	) c
)
AND pasrelanceabo = 0
AND codepostal <> ''
AND statutnpai <= 2";

$db = get_db();
?>---> Résultat : <b><?= $db->all( $sql )[0] ?></b>
---> Conclusion : le critère Abo_payé n'est pas satisfaisant
</pre>


<pre><h4>Vieux abonnés sans critère abo_payé </h4>
<?php
$sql = "SELECT COUNT(*) AS Nbre
FROM adresse
WHERE reffiche NOT  IN (
    SELECT `reffiche`
	FROM (SELECT `reffiche`, MAX(dateapplication)
		FROM critere_adr
		WHERE critere = 'Abo_payé'
		GROUP BY `reffiche`
		HAVING MAX(dateapplication) < '$date_abo'
	) c
)
AND date_finabn_rezo BETWEEN '1900-01-01' AND '$fin_abo'
AND DATEDIFF(date_finabn_rezo, date_abn_rezo) > 360
AND pasrelanceabo = 0
AND codepostal <> ''
AND statutnpai <= 2";

$db = get_db();
?>---> Résultat : <b><?= $db->all( $sql )[0] ?></b>
---> Conclusion : le critère Abo_payé n'est pas satisfaisant
</pre>

<pre><h4>Vieux abonnés avec critère abo_payé et sans champ abo_rezo = Anomalie 4D</h4>
<?php
$sql = "SELECT COUNT(*) AS Nbre
FROM adresse
WHERE reffiche IN (
    SELECT `reffiche`
	FROM (SELECT `reffiche`, MAX(dateapplication)
		FROM critere_adr
		WHERE critere = 'Abo_payé'
		GROUP BY `reffiche`
		HAVING MAX(dateapplication) < '$date_abo'
	) c
)
AND NOT (date_finabn_rezo BETWEEN '1900-01-01' AND '$fin_abo'
	AND DATEDIFF(date_finabn_rezo, date_abn_rezo) > 360
)
AND pasrelanceabo = 0
AND codepostal <> ''
AND statutnpai <= 2";

$db = get_db();
?>---> Résultat : <b><?= $db->all( $sql )[0] ?></b>
---> Conclusion : Anomalie !
</pre>


<pre><h4>Vieux destinataires : date de la dernière revue reçue avant le <?=$fin_rev?>,</h4>
parmi les Vieux abonnés : fin d'abonnement (fiche adresse) avant le <?=$fin_abo?>.
<?php
$sql = "SELECT COUNT(*) AS Nbre
FROM adresse
WHERE reffiche IN (
    SELECT `reffiche`
	FROM (SELECT `reffiche`, MAX(dateapplication) AS dateapplication
		FROM critere_adr
		WHERE critere LIKE 'REVUE\_%'
		GROUP BY `reffiche`
		HAVING MAX(dateapplication) < '$fin_rev'
	) c
)
AND date_finabn_rezo BETWEEN '1900-01-01' AND '$fin_abo'
AND DATEDIFF(date_finabn_rezo, date_abn_rezo) > 360
AND pasrelanceabo = 0
AND codepostal <> ''
AND statutnpai <= 2";

$db = get_db();
?>---> Résultat : <b><?= $db->all( $sql )[0] ?></b>
</pre>

<pre><h4>Vieux abonnés : date de fin avant le <?=$fin_abo?></h4>
Pas de contact :
    - aucune ligne Cogilog après le <?=$fin_abo?>.
    - aucun critère de petition après le <?=$fin_abo?>.
<?php
$sql = "SELECT COUNT(*) AS Nbre
FROM adresse
WHERE reffiche IN (
    SELECT `reffiche`
	FROM (SELECT `reffiche`, MAX(dateapplication) AS dateapplication
		FROM critere_adr
		WHERE critere LIKE 'REVUE\_%'
		GROUP BY `reffiche`
		HAVING MAX(dateapplication) < '$fin_rev'
	) c
)
AND date_finabn_rezo BETWEEN '1900-01-01' AND '$fin_abo'
AND DATEDIFF(date_finabn_rezo, date_abn_rezo) > 360

AND reffiche NOT IN (
    SELECT `reffiche`
	FROM lignefacturecogilog
	WHERE datepiece >= '$fin_abo'
) 
AND  reffiche NOT IN (
    SELECT `reffiche`
		FROM critere_adr
		WHERE (critere LIKE '%Petition%' OR critere LIKE '%Pétition%'
		OR critere LIKE '%Peti\_%' OR critere LIKE '%Péti\_%')
		AND dateapplication >= '$fin_abo'
)
AND pasrelanceabo = 0
AND codepostal <> ''
AND statutnpai <= 2";

$db = get_db();

?>---> Résultat : <b><?= $db->all( $sql )[0] ?></b>
</pre>

<?php }?>
</div>
<style>
	.stats-0 h4 {
		margin-bottom: 6px;
	}
</style>