<table><caption><?=$node["nm"]?></caption>
<tbody><?php
		$db = db::get(DBTYPE . '://' . DBUSER . ':' . DBPASSWORD . '@' . DBSERVER . '/' . DBNAME);
		$rows = $db->all("
			SELECT d.param, d.value
			FROM 
				node_query d
			WHERE 
				d.id = ?"
			, array((int)$node['id'])
		);
		$exists = count($rows) > 0;

		foreach($rows as $row){
			$name = $row["param"] . '|value';
			$input =	'<textarea name="' . $name . '" style="width:100%;" rows="14">' . ifNull($row["value"]) . '</textarea>'
			;
			echo	'<div><label class="ui-state-default ui-corner-all">' . $row["param"] . '</label>'
				. $input
			;
		}
		?></tbody>
<tfoot><tr><td colspan="99"><?= count($rows) . ' ligne' . (count($rows) > 1 ? 's' : '')?></tr>
</tfoot>
</table>