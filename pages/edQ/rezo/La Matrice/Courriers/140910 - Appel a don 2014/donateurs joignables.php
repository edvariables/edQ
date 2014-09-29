<h3>Donateurs NPAI &lt;= 2 </h3>
<pre>
Donateurs tout temps avec adresse joignable en France
qui ne refuse pas les relances financières par courrier
<?php
$sql = "SELECT COUNT(*) AS Nbre
FROM adresse
WHERE reffiche IN (
    SELECT `reffiche` FROM don_adr
    )
AND pays = ''
AND codepostal <> ''
AND statutnpai <= 2
AND pasrelancefinancierecourrier = 0";

$db = get_db();

?>

---> Résultat : <b><?= $db->all( $sql )[0] ?></b>
</pre>