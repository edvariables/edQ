<h3>En prélèvement en 2013 et 2014 et n'ayant pas effectués de dons depuis le 01/01/2013</h3>
<pre>
<?php
$db = get_db();

$sql = "SELECT COUNT(*) AS Nbre
FROM (SELECT DISTINCT idfiche
FROM prlv_histo_detail p
WHERE p.idfiche IN (
	SELECT idfiche
	FROM `prlv_histo_detail`
	WHERE datepvt >= '2014-01-01') 
AND p.idfiche IN (
	SELECT idfiche
	FROM `prlv_histo_detail`
	WHERE datepvt BETWEEN '2013-01-01' AND '2014-01-01') 
AND p.idfiche NOT IN (
	SELECT reffiche
	FROM don_adr d
	WHERE d.datedudon >= '2013-01-01') 
) d
";

?>
---> Résultat : <b><?= $db->all( $sql )[0] ?></b>

<?php
$sql = "SELECT COUNT(*) AS Nbre
FROM (SELECT DISTINCT idfiche
FROM prlv_histo_detail p
WHERE p.idfiche IN (
	SELECT idfiche
	FROM `prlv_histo_detail`
	WHERE datepvt >= '2014-01-01') 
) d
";

?>
---> sur un total de <b><?= $db->all( $sql )[0] ?></b> personnes en prélèvement en 2014
</pre>