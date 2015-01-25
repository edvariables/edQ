<?php 
	$db = get_db();
	 $rows = $db->all("
		SELECT IdContact, Name
		FROM 
			contact c
		WHERE
			c.Name <> ''
		ORDER BY
			c.Name
		LIMIT ?, ?"
		, array(0, 30)
	);
?><table class="edq" style="overflow: scroll;"><caption><?=$node["nm"]?></caption>
	<thead><tr>
	<th>#</th>
	<th>Nom</th>
	</thead>
	<tbody><?php
	foreach($rows as $row){
		?><tr>
		<td><?=$row["IdContact"]?>
		<td><?=$row["Name"]?>
		</tr><?php
	}
	?></tbody>
	<tfoot><tr><td colspan="99"><?= count($rows) . ' ligne' . (count($rows) > 1 ? 's' : '')?></tr>
	</tfoot>
</table>