<h3>Donateurs à relancer </h3>
<pre>
Donateurs tout temps avec adresse joignable en France
qui ne refuse pas les relances financières par courrier
<?php
$sqlNotIn = array();

echo "<br>exclus : gros donateurs récents (> 20 € en 2014 ou > 50€ entre 2011 et 2013)";
$sqlNotIn[] = "SELECT reffiche
FROM adresse
WHERE (reffiche IN (
    
SELECT `reffiche`
    FROM `vw_dons_annee`
	WHERE `annee` = 2014 AND dons > 20
))

OR (
    reffiche IN (
SELECT `reffiche`
    FROM `vw_dons_annee`
	WHERE `annee` BETWEEN 2011 AND 2013
    GROUP BY reffiche
    HAVING SUM(dons) > 50
)
    )
	";

echo "<br>exclus : Donateurs web (ADETAL) des 6 derniers mois";
$sqlNotIn[] = "SELECT reffiche
FROM `don_adr`
WHERE `typedon` = 'ADETAL'
AND datedudon > DATE_SUB(NOW(), INTERVAL 6 MONTH)
";


echo "<br>exclus : En prélèvement en 2014 et ayant effectués des dons depuis le 01/01/2013";
$sqlNotIn[] = "SELECT idfiche
FROM prlv_histo_detail p
WHERE p.idfiche IN (
	SELECT idfiche
	FROM `prlv_histo_detail`
	WHERE datepvt >= '2014-01-01')
AND p.idfiche IN (
	SELECT reffiche
	FROM don_adr d
	WHERE d.datedudon >= '2013-01-01') 
";


echo "<br>exclus : Donateurs NEF en 2014";
$sqlNotIn[] = "
SELECT `reffiche`
FROM `don_adr`
WHERE datedudon > DATE_SUB(NOW(), INTERVAL 1 YEAR)
AND typedon IN ('ADNEF')
";


echo "<br> synthese";
$sql = "SELECT COUNT(*) AS Nbre
FROM adresse
WHERE reffiche IN (
    SELECT `reffiche` FROM don_adr
    )
AND pays = ''
AND codepostal <> ''
AND statutnpai < 2
AND TypeAbonne <> 2
AND TypeAbonne <> 3
AND signataire = 0
AND pasrelancefinancierecourrier = 0
";



foreach($sqlNotIn as $notIn)
	$sql .= " AND reffiche NOT IN (
		" . $notIn
		. ")
	";
$db = get_db();

?>

---> Résultat : <b><?= $db->all( $sql )[0] ?></b>
</pre>