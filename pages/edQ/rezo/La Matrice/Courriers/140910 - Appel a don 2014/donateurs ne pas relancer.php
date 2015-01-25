<h3>Donateurs à ne pas relancer</h3>
<pre>
Donateurs dans les 4 ans "pasrelancefinancierecourrier" ou "pasderelancefinanciereweb"
<?php
$sql = "SELECT 'pasrelancefinancierecourrier' AS Critere, COUNT(*) AS Nbre
FROM adresse
WHERE (reffiche IN (
    
SELECT `reffiche`
FROM `don_adr`
WHERE datedudon > DATE_SUB(NOW(), INTERVAL 4 YEAR)
    )
)
AND pasrelancefinancierecourrier = 1

UNION

SELECT 'pasderelancefinanciereweb', COUNT(*) AS Nbre
FROM adresse
WHERE (reffiche IN (
    
SELECT `reffiche`
FROM `don_adr`
WHERE datedudon > DATE_SUB(NOW(), INTERVAL 4 YEAR)
    )
)
AND pasderelancefinanciereweb = 1


UNION

SELECT 'total', COUNT(*) AS Nbre
FROM adresse
WHERE (reffiche IN (
    
SELECT `reffiche`
FROM `don_adr`
WHERE datedudon > DATE_SUB(NOW(), INTERVAL 4 YEAR)
    )
)
AND (pasrelancefinancierecourrier = 1
OR pasderelancefinanciereweb = 1)";

$db = get_db();

?>

---> Résultat : <?php 
$args = array( 'rows' => $db->all( $sql ) );
node('/_html/table/rows', $node, 'call', $args);
?>
</pre>