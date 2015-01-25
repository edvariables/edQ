<h3>Adresses marquées comme abonnées à la revue</h3>
<pre>Abonnés par tranche de date de fin d'abonnement
Payé : critère Abo_payé + 1 an
Non (payé) : pas de critère Abo_payé
<?php
$sql = "
SELECT 
	CONCAT( YEAR(date_finabn_rezo)
	, ' sem '
	, (MONTH(date_finabn_rezo) - 1) DIV 6 + 1) AS `Fin d'abonmnt`
, COUNT(c.fin_abo_paye) AS `payé`
, COUNT(*) - COUNT(c.fin_abo_paye) AS `non`
, COUNT(*) AS 'Total'
, ROUND((SUM(c.Nb_Criteres)/SUM(IF(c.fin_abo_paye IS NULL, 0, 1)) - 1) * 100)  AS `anomalie`
, SUM(r.Nb_Revues) AS `Revues`
, FORMAT(AVG(r.Nb_Revues), 1) AS `Revues moy`
, COUNT(*) - COUNT(r.Nb_Revues) AS `Sans revue`
, SUM(IF(r.Nb_Revues IS NULL AND NOT c.fin_abo_paye IS NULL, 1, 0)) AS `Oubliés`
FROM adresse a

/* Abo_payé */
LEFT JOIN (SELECT `reffiche`
	, CONCAT(YEAR(dateapplication)+1
		, ' sem '
		, (MONTH(dateapplication) - 1) DIV 6 + 1) AS fin_abo_paye
	, CAST(CONCAT(YEAR(dateapplication), '-', MONTH(dateapplication), '-01') AS DATE) AS debut_abo_paye
	, CAST(CONCAT(YEAR(DATE_ADD(dateapplication, INTERVAL 18 MONTH)), '-', MONTH(DATE_ADD(dateapplication, INTERVAL 18 MONTH)), '-01') AS DATE) AS fin_abo_paye_6M
	, COUNT(*) AS Nb_Criteres
		FROM critere_adr
		WHERE critere = 'Abo_payé'
		GROUP BY `reffiche`
		, CONCAT(YEAR(dateapplication), (MONTH(dateapplication) - 1) DIV 6 + 1)
	) c
	ON c.reffiche = a.reffiche
	/*AND c.fin_abo_paye = CONCAT( YEAR(date_finabn_rezo)
		, ' sem '
		, (MONTH(date_finabn_rezo) - 1) DIV 6 + 1)*/
	AND date_finabn_rezo BETWEEN c.debut_abo_paye AND c.fin_abo_paye_6M
	

/* REVUES reçues à la fin de l'abonnement */
LEFT JOIN (SELECT `reffiche`, CONCAT(YEAR(dateapplication)
	, ' sem '
	, (MONTH(dateapplication) - 1) DIV 6 + 1) AS dateapplication
	, CAST(CONCAT(YEAR(DATE_SUB(dateapplication, INTERVAL 2 MONTH)), '-', MONTH(DATE_SUB(dateapplication, INTERVAL 2 MONTH)), '-01') AS DATE)
		AS debut_abo
	, CAST(CONCAT(YEAR(DATE_ADD(dateapplication, INTERVAL 2 MONTH)), '-', MONTH(DATE_ADD(dateapplication, INTERVAL 2 MONTH)), '-01') AS DATE) 
		AS fin_abo
	, COUNT(*) AS Nb_Revues
		FROM critere_adr
		WHERE critere LIKE 'REVUE\_%'
		GROUP BY `reffiche`
		, CONCAT(YEAR(dateapplication)
			, ' sem '
			, (MONTH(dateapplication) - 1) DIV 6 + 1)
	) r
	ON r.reffiche = a.reffiche
	/*AND date_finabn_rezo BETWEEN r.debut_abo AND r.fin_abo*/
	 AND r.dateapplication = CONCAT(YEAR(date_finabn_rezo)
	, ' sem '
	, (MONTH(date_finabn_rezo) - 1) DIV 6 + 1)
	
WHERE  a.date_abn_rezo > '1980-01-01'

GROUP BY CONCAT(YEAR(date_finabn_rezo), ' sem ', (MONTH(date_finabn_rezo) - 1) DIV 6 + 1)

ORDER BY `Fin d'abonmnt`
";

$db = get_db();

page::call('/_format/csv/download link', array(
	'csv--node' => $node['id'],
	'csv--file' => $node['nm'],
));?>
<br>
<?php
$uid = uniqid('table');
$args = array( 'rows' => $db->all( $sql ), 'uid' => $uid );
node('/_html/table/rows', $node, 'call', $args);
?>
</pre>
<style>
	#<?=$uid?> td{
		padding: 0 4px;
		text-align: right;
	}
	#<?=$uid?> td:nth-child(1){
		text-align: left;
	}
</style>