<?php
	include(dirname(__FILE__) . '/../dataSource.php');
	global $db;
	$rows = $db->all("
		SELECT p.`domain`, p.`param`, p.`text`, p.`valueType`, p.`icon`, p.`defaultValue`, p.`comment`, p.`sortIndex`
	 	, COUNT(n.id) AS nbUse
		FROM 
			node_params p
	 	LEFT JOIN
	 		 node_param n
	 		 ON p.domain = n.domain
	 		 AND p.param = n.param
	 	GROUP BY
	 	 	p.`domain`, p.`param`, p.`text`, p.`valueType`, p.`icon`, p.`defaultValue`, p.`comment`, p.`sortIndex`
		ORDER BY
			p.`domain`, p.`sortIndex`, p.`text`, p.`param`
		LIMIT ?, ?"
		, array(0, 99)
	);
	$uid = uniqid('form-');
?><table id="<?=$uid?>" class="edq" style="overflow: scroll;">
	 <caption>Liste des paramètres de noeuds</caption>
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
	 	 <a href="tree/db.php?operation=get_view&id=<?=1161?>&vw=file.call&get=content&f-domain=<?=$row["domain"]?>&f-param=<?=$row["param"]?>"
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
#<?=$uid?> tbody > tr > th {
	 font-size: smaller;
	 font-weight: normal;
	 white-space: nowrap;
	 border: 1px solid black;
}
#<?=$uid?> tbody {
	 background-color: white;
}
#<?=$uid?> tbody > tr:hover {
	 background-color: #EEEEBB;
}
#<?=$uid?> tbody > tr > *{
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