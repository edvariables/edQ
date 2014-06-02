<?php
	include(dirname(__FILE__) . '/../dataSource.php');
	global $db;
	$rows = $db->all("
		SELECT IdContact, Name,
	 	 `EMail`, `Phone1`, `Phone2`, `Address`, `ZipCode`, `City`
		FROM 
			contact c
		WHERE
			c.Name <> ''
		ORDER BY
			c.Name
		LIMIT ?, ?"
		, array(0, 30)
	);
	$uid = uniqid('form-');
?><table id="<?=$uid?>" class="edq" style="overflow: scroll;"><caption><?=$node["nm"]?></caption>
	<thead><tr>
	<th>#</th>
	<th>Nom</th>
	<th>Email</th>
	<th>Téléphone</th>
	<th>Adresse</th>
	<th>Ville</th>
	</thead>
	<tbody><?php
	foreach($rows as $row){
		?><tr>
		<td><?=$row["IdContact"]?>
		<td><?=$row["Name"]?>
		<td><?=$row["EMail"]?>
		<td><?=$row["Phone1"]?><?=$row["Phone2"] == null || $row["Phone2"] == '' ? '' : '<br/>' . $row["Phone2"]?>
		<td><?=$row["Address"] == null ? '' : str_replace('\n', '<br>', $row["Address"])?>
		<td><?=$row["ZipCode"]?><?=$row["City"] == null || $row["City"] == '' ? '' : ' ' . $row["City"]?>
		</tr><?php
	}
	?></tbody>
	<tfoot><tr><td colspan="99"><?= count($rows) . ' ligne' . (count($rows) > 1 ? 's' : '')?></tr>
	</tfoot>
</table>
<style>
#<?=$uid?> {
	border-spacing: 0px;
	 border-collapse: collapse; 
	 border: 1px outset #333333;
}
#<?=$uid?> tbody {
	 background-color: white;
}
#<?=$uid?> tbody > tr > td {
	padding: 1px 4px 1px 6px;
	 border: 1px solid #333333;
}
#<?=$uid?> tbody > tr > td:nth-of-type(1) {
	text-align: right;
}
#<?=$uid?> thead > tr > th {
	text-align: center;
	 border: 1px solid #333333;
}
</style>