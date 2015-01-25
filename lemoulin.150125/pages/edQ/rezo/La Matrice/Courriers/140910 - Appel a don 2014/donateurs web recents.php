<h3>Donateurs récents</h3>
<pre>
Donateurs web (ADETAL) des 6 derniers mois
<?php
$sql = "SELECT COUNT(*) AS Nbre
FROM adresse
WHERE (reffiche IN (
    
SELECT `reffiche`
FROM `don_adr`
WHERE `typedon` = 'ADETAL'
AND datedudon > DATE_SUB(NOW(), INTERVAL 6 MONTH)
    )
)";

$db = get_db();

?>

---> Résultat : <b><?= $db->all( $sql )[0] ?></b>


Donateurs web (ADETAL) des 12 derniers mois
<?php
$sql = "SELECT COUNT(*) AS Nbre
FROM adresse
WHERE (reffiche IN (
    
SELECT `reffiche`
FROM `don_adr`
WHERE `typedon` = 'ADETAL'
AND datedudon > DATE_SUB(NOW(), INTERVAL 12 MONTH)
    )
)";

$db = get_db();

?>

---> Résultat : <b><?= $db->all( $sql )[0] ?></b>
</pre>