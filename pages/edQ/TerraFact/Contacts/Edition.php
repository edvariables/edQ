<?php
	 $db = get_db();

	 $search = 0;
	 if(isset($_REQUEST["f-IdContact"]))
	 	 $search = (int)$_REQUEST["f-IdContact"];
	 else
	 	 $search = 2000; //exemple
	 $rows = $db->all("
		SELECT *
		FROM 
			contact c
		WHERE
			c.IdContact = ?
		LIMIT 1"
		, array( $search )
	);

	$uid = uniqid('form-');
?><table id="<?=$uid?>" class="edq" style="overflow: scroll;"><caption><?=$node["nm"]?></caption>
	<tbody><?php
	 if($rows > 0)
	foreach($rows[0] as $col => $value){
		?><tr>
		<th><?=$col?>
		<td><?=$value?>
		</tr><?php
	}
	?></tbody>
	<tfoot><tr><td colspan="99"><?= count($rows) == 0 ? ' <small>' . $search . ' <i> introuvable</i></small>' : ''?></tr>
	</tfoot>
</table>
<style>
#<?=$uid?> {
	border-spacing: 0px;
	 border-collapse: collapse; 
	 border: 1px outset #333333:
}
#<?=$uid?> tbody {
	 background-color: white;
}
#<?=$uid?> tbody > tr > *{
	padding: 1px 4px 1px 6px;
	 border: 1px solid #DDDDDD;
	 white-space: pre;
}
#<?=$uid?> tbody th {
	text-align: left;
	 border: 2px solid #333333:
}
</style>