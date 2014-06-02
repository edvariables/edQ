<?php
	include(dirname(__FILE__) . '/../dataSource.php');
	global $db;
	 $domain = isset($_REQUEST["f-domain"]) ? $_REQUEST["f-domain"] : '';
	 $param = isset($_REQUEST["f-param"]) ? $_REQUEST["f-param"] : '';
	$rows = $db->all("
		SELECT p.`domain`, p.`param`, p.`text`, p.`valueType`, p.`icon`, p.`defaultValue`, p.`comment`, p.`sortIndex`
	 	, COUNT(n.id) AS nbUse
		FROM 
			node_params p
	 	LEFT JOIN
	 		 node_param n
	 		 ON p.domain = n.domain
	 		 AND p.param = n.param
	 	WHERE
	 		p.domain = ?
	 	AND	p.param = ? 
	 	GROUP BY
	 	 	p.`domain`, p.`param`, p.`text`, p.`valueType`, p.`icon`, p.`defaultValue`, p.`comment`, p.`sortIndex`
		ORDER BY
			p.`domain`, p.`sortIndex`, p.`text`, p.`param`
		LIMIT ?, ?"
		, array($domain, $param, 0, 1)
	);
	$uid = uniqid('form-');
if(count($rows) === 0) {
	 ?>aucun élément<?php
}
else {
?><table id="<?=$uid?>" class="edq" style="overflow: scroll;">
	 <caption>Paramètre de noeud <?=$rows[0]['domain']?>.<?=$rows[0]['param']?></caption>
	<thead><tr>
	<th></th>
	<th></th>
	<th>Domaine</th>
	<th>Paramètre</th>
	<th>Texte</th>
	<th>Type</th>
	<th>Icône</th>
	<th>Par défaut</th>
	<th>Commentaire</th>
	</thead>
	<tbody><?php
	$curDomain = '';
	foreach($rows as $row){
		?><tr>
	 	<th><?=$row["sortIndex"]?>
	 	 <a href="tree/db.php?operation=get_view&id=<?=$node['id']?>&vw=fileCall&get=content"
	 	 	 onclick="$.get(this.getAttribute('href'), function(html){
	 	 	 	 $('<div></div>').appendTo('body').html(html).dialog({
	 	 	 	 	 title: '<?=str_replace('\'', '\\\'', $row["domain"] . '.' . $row["param"])?>',
	 	 	 	 	 width: 'auto',
	 	 	 	 	 height: 'auto'
	 	 	 	 });
	 	 	 	});
	 	 	 return false;">éditer</a>
		<th><?=$row["nbUse"] == 0 ? '' : '(' . $row["nbUse"] . ')'?>
		<td><?if($curDomain != $row["domain"]){
	 	 	 $curDomain = $row["domain"];
	 	 	 echo($row["domain"]);
	 	 }?>
		<td><?=$row["param"]?>
		<td><?=$row["text"]?>
		<td><?=$row["valueType"]?>
		<td><?=$row["icon"]?>
		<td><pre><?=htmlentities(strlen($row["defaultValue"]) > 255 ? substr($row["defaultValue"], 0, 252) . '...' : $row["defaultValue"])?></pre>
		<td><?=$row["comment"]?>
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
</style><?
}?>