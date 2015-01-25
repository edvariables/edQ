<h3>Petits donateurs récents</h3>
<pre>
Donateurs entre 2011 et 2014 qui 
- n'ont pas donné plus de 20€ en 2014
- et dont le cumul 2011 à 2013 ne dépassent pas 50€
<?php
$sql = "SELECT COUNT(*) AS Nbre
FROM adresse
WHERE (reffiche IN (
    
SELECT `reffiche`
    FROM `vw_dons_annee`
	WHERE `annee` = 2014 AND dons <= 20
)
    AND reffiche  NOT IN (
    
SELECT `reffiche`
    FROM `vw_dons_annee`
	WHERE `annee` BETWEEN 2011 AND 2013
    GROUP BY reffiche
    HAVING SUM(dons) > 50
)
    )

OR (
    reffiche IN (
SELECT `reffiche`
    FROM `vw_dons_annee`
	WHERE `annee` BETWEEN 2011 AND 2013
    GROUP BY reffiche
    HAVING SUM(dons) <= 50
)
    AND reffiche  NOT IN (
    
SELECT `reffiche`
    FROM `vw_dons_annee`
	WHERE `annee` = 2014 AND dons > 20
)
    )";

$db = get_db();

?>

---> Résultat : <b><?= $db->all( $sql )[0] ?></b>
</pre>