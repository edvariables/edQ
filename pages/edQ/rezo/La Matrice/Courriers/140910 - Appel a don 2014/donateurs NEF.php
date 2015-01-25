<h3>Donateurs NEF</h3>
<pre>
Donateurs NEF dans les 4 ans
<?php
$sql = "SELECT '1 an' AS Annee, COUNT(*) AS Nbre
FROM adresse
WHERE (reffiche IN (
    
SELECT `reffiche`
FROM `don_adr`
WHERE datedudon > DATE_SUB(NOW(), INTERVAL 1 YEAR)
AND typedon IN ('ADNEF')
    )
)

UNION

SELECT '2 ans', COUNT(*) AS Nbre
FROM adresse
WHERE (reffiche IN (
    
SELECT `reffiche`
FROM `don_adr`
WHERE datedudon > DATE_SUB(NOW(), INTERVAL 2 YEAR)
AND typedon IN ('ADNEF')
    )
)

UNION

SELECT '4 ans', COUNT(*) AS Nbre
FROM adresse
WHERE (reffiche IN (
    
SELECT `reffiche`
FROM `don_adr`
WHERE datedudon > DATE_SUB(NOW(), INTERVAL 4 YEAR)
AND typedon IN ('ADNEF')
    )
)";

$db = get_db();

?>

---> Résultat : <?php 
$args = array( 'rows' => $db->all( $sql ) );
node('/_html/table/rows', $node, 'call', $args);
?>
</pre>