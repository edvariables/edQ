<?php
	 $db = get_db();

	 $search = 0;
	 if(isset($_REQUEST["f--IdContact"]))
	 	 $search = (int)$_REQUEST["f--IdContact"];
	 else
	 	 $search = 1; //exemple
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
?><form id="<?=$uid?>" method="POST" action="<?=url_view( $node )?>" autocomplete="off">
<input type="hidden" name="q--limit" value="9999"/>
<table class="edq" style="overflow: scroll;">
	<caption><?=$node["nm"]?></caption>
	<tbody><?php
	if(count($rows) > 0)
		foreach($rows[0] as $col => $value){
			?><tr>
			<th><?=$col?>
			<td><?=$value?>
			</tr><?php
		}
	?></tbody>
	<tfoot><tr><td colspan="99"><?= count($rows) == 0 ? ' <small>' . $search . ' <i> introuvable</i></small>' : ''?><?php
		/* submit */
		?><input type="submit" value="Enregistrer"/></tr>
	</tfoot>
</table>
</form>
<style>
#<?=$uid?> table {
	border-spacing: 0px;
	 border-collapse: collapse; 
	 border: 1px outset #333333;
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
	/*border: 2px solid #333333;*/
}
#<?=$uid?> tfoot td {
	text-align: left;
	/*border: 2px solid #333333;*/
}
</style>

<?= isset($view) ? $view->searchScript($uid) : '$view no set'?>