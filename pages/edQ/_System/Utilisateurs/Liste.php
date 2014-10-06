<?php /* é */
	$node = node($node, __FILE__);
	$db = get_db();
	$rows = $db->all("
		SELECT
			c.IdContact, c.Name,
	 		`EMail`, `Phone1`, `Phone2`,
			u.`UserType`, u.Enabled
		FROM 
			contact c
		INNER JOIN
			user u
			ON c.IdContact = u.IdUser
		WHERE
			u.Enabled = ?
	 	AND
			u.UserType >= ?
		ORDER BY
			c.Name
		LIMIT ?, ?"
		, array(1, $_SESSION['edq-user']['UserType'], 0, 100)
	);
	$uid = uniqid('form-');
?><table id="<?=$uid?>" class="edq" style="overflow: scroll;"><caption><?=$node["nm"]?></caption>
	<thead><tr>
	 <th/>
	<th>#</th>
	<th>Nom</th>
	<th>Email</th>
	<th>Téléphone(s)</th>
	<th>Type</th>
	<th>Actif</th>
	</thead>
	<tbody><?php
	$viewerid = node('Edition', $node, 'id');
	foreach($rows as $row){
		?><tr>
	 	 <th><a href="view.php?id=<?=$viewerid?>&f--IdContact=<?=$row["IdContact"]?>"
	 	 	 onclick="$.get(this.getAttribute('href'), function(html){
	 	 	 	 $('<div></div>').appendTo('body').html(html).dialog({
	 	 	 	 	 title: '<?=$row['IdContact']?>',
	 	 	 	 	 width: 'auto',
	 	 	 	 	 height: 'auto'
	 	 	 	 });
	 	 	 	});
	 	 	 return false;">éditer</a>
	 	 </th>
		<td><?=$row["IdContact"]?>
		<td><?=$row["Name"]?>
		<td><?=$row["EMail"]?>
		<td><?=$row["Phone1"]?><?=$row["Phone2"] == null || $row["Phone2"] == '' ? '' : '<br/>' . $row["Phone2"]?>
		<td><?=$row["UserType"]?>
		<td><input type="checkbox"<?=$row['Enabled'] ? ' checked="checked"' : ''?>/>
		</tr><?php
	}
	?></tbody>
	<tfoot><tr><td/><td colspan="99"><?= count($rows) . ' utilisateur' . (count($rows) > 1 ? 's' : '')?></tr>
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
#<?=$uid?> tbody > tr:hover {
	background-color: #FAFAFA;
}
#<?=$uid?> tbody > tr > th > a {
	font-weight: normal;
	font-size: smaller;
	 color: darkblue;
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