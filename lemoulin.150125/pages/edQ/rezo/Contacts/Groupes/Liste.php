<?php 
/* Liste des groupes
Les paramètres de filtres commencent par f-- suivi du nom du champ
*/
if(false){
?><pre><?php foreach($_REQUEST as $k => $v)
	 if(preg_match('/^f--/', $k)){
	 	 echo $k . ' => ' . $v . '<br>';
	 }
?></pre><?php
}

$db = get_db();
$sql = " SELECT * FROM (
	SELECT `g`.`ReffFiche` AS `RefFiche`, `gd`.`id_dep` AS `id_dep`
	, substr(`g`.`CodePostal`,1,2) AS NumDepartement
	, IFNULL(`gd`.`departement`, substr(`g`.`CodePostal`,1,2)) AS `departement`
	,`g`.`Association` AS `Association`,`g`.`Prenom` AS `Prenom`,`g`.`Nom` AS `Nom`
	,`g`.`CodePostal`,`g`.`Ville` AS `Ville`,`g`.`Pays` AS `Pays`
	,`g`.`Telephone` AS `Telephone`,`g`.`Portable` AS `Portable`
	, IF(`g`.`Email_priv` IS NULL OR `g`.`Email_priv` = '', `g`.`Email`, `g`.`Email_priv`) AS `Email`
	,`g`.`Region` AS `Region`,`g`.`SiteWeb` AS `SiteWeb`,`g`.`Type` AS `Type`
	,`g`.`Descriptif` AS `Descriptif`,`g`.`Adhesion` AS `Adhesion`

	FROM `groupes212`.`groupes` `g` 
	LEFT JOIN `groupes212`.`departements` `gd`
		ON `gd`.`id_dep` = substr(`g`.`CodePostal`,1,2)
) a";

	$where = 0;
	$params = array();
	//complète la requête
	foreach($_REQUEST as $k => $arr)
		if(preg_match('/^f--/', $k) && count($arr) > 0){
	 		$sql .= (($where++ > 0) ? ' AND (' : ' WHERE (');
	 	 	$or = 0;
	 	 	foreach($arr as $v){
				if($v === '')
					 $arr[] = null; //ajoute un élément null à parcourir
				 
	 	 	 	$sql .= (($or++ > 0) ? ' OR ' : ' ')
	 	 	 	 	 . '`' . substr($k, 3) . '`';
				if( $v === null)
					 $sql .= ' IS NULL';
				else {
	 	 	 		if($k == 'f--Adhesion' && $v != '')
	 	 	 	 		$sql .= ' LIKE CONCAT(\'%\', ?, \'%\') ';
	 	 	 		else 
	 	 	 	 		$sql .= ' = ?';
	 	 	 	 	$params[] = $v;
					if($k == 'f--departement' && $v != ''){
	 	 	 	 		$sql .= ' OR EXISTS (SELECT *
							FROM `groupes212`.groupes_autres_departements gad
							LEFT JOIN `groupes212`.`departements` `gd`
								ON `gd`.`id_dep` = gad.NumDepartement
							WHERE gad.RefFiche = a.RefFiche AND gd.departement = ?) ';
						$params[] = $v;
					}
				} 
			}
	 	 	$sql .= ')';
	 	 }
	 //var_dump($params);
	 //var_dump($sql);
	 $maxRows = isset($_REQUEST['q--limit']) ? (int)$_REQUEST['q--limit'] : 10;
	 array_push($params, 0, $maxRows);
	 $rows = $db->all( $sql . ' LIMIT ?, ?', $params );
	 /* valeurs disctinctes */
	 $distincts = $db->all("
		SELECT DISTINCT IFNULL(`gd`.`departement`, substr(`g`.`CodePostal`,1,2)) AS `departement`
			,`g`.`Ville` AS `Ville`,`g`.`Pays` AS `Pays`,`g`.`Region` AS `Region`,`g`.`Type` AS `Type`
		FROM `groupes212`.`groupes` `g` 
		LEFT JOIN `groupes212`.`departements` `gd` ON `gd`.`id_dep` = substr(`g`.`CodePostal`,1,2)
		LIMIT ?, ?"
		, array(0, 2000)
	);

/* insertion d'une liste d'éléments de colonnes par sélection multiple */
$htmlFilter = function($field) use($distincts){
	$values = array();
	if($field == 'Adhesion'){
	 	 $values[''] = null;
	 	 for($i = (int)date('Y'); $i > 1997; $i--)
	 	 	 $values[$i] = null;
	}
	else {
	 	 foreach($distincts as $row)
	 		  if(!isset($values[$row[$field]]))
	 		  	 $values[$row[$field]] = 1;
			  else
		 	 	 $values[$row[$field]]++;
	}
	$selected = array();
	if(isset($_REQUEST['f--' . $field]))
	 	 foreach($_REQUEST['f--' . $field] as $v)
		 	 $selected[$v] = 1;
	//tri
	if($field == 'Adhesion') krsort($values);
	else ksort($values);
	//html select
	?><select class="chosen" name="f--<?=$field?>[]" multiple><?php
	//crée les options
	foreach($values as $value => $count){
	 	 ?><option value="<?=$value?>" <?=isset($selected[$value]) ? 'selected="selected"' : ''?>>
	 	 <?=$value . ($count ? ' (' . $count . ')' : '')?></option><?php
	}
	?></select><?php
};
$uid = uniqid('form');
?>
<form id="<?=$uid?>" method="POST" action="view.php?id=<?=$node['id']?>&vw=file.call" autocomplete="off">
<input type="hidden" name="q--limit" value="2000"/>
<table class="edq" style="overflow: scroll;">
	<caption style="text-align: left;"><?= count($rows) . ' groupe' . (count($rows) > 1 ? 's' : '')?><?= $maxRows == count($rows) ? ' ou plus' : ''?>
		<input type="submit" value="Rechercher" style="margin-left: 2em;"/><a href="" style="margin-left: 2em;" onclick="$(this).parents('form:first').find('select').val([]); return false;">annuler les sélections</a>
		<textarea id="<?=$uid?>-emails"></textarea><a href="#" onclick="$(this).prev().select(); return false;" style="font-size: small; font-style: italic;">&nbsp;sélectionner tout le texte</a>
	</caption>
	<thead><tr>
	<th></th>
	<th>Région</th>
	<th>Département</th>
	<th>Ville</th>
	<th>Association</th>
	<th>Contact</th>
	<th>Email</th>
	<th>Adhesion</th>
	<th>Type</th>
	<th>Descriptif</th>
	<th>SiteWeb</th>
	</tr>
	<tr class="filters">
	<th><input id="<?=$uid?>-emails-selector" type="checkbox" checked="checked"/></th>
	<th><?=$htmlFilter('Region')?></th>
	<th><?=$htmlFilter('departement')?></th>
	<th></th>
	<th></th>
	<th></th>
	<th></th>
	<th><?=$htmlFilter('Adhesion')?></th>
	<th><?=$htmlFilter('Type')?></th>
	<th></th>
	<th></th>
	</tr>
	</thead>
	<tbody><?php
	foreach($rows as $row){
		?><tr>
		<th><?php if($row["Email"] != null) {?><input type="checkbox" checked="checked"/><?php }?></th>
		<td><?=$row["Region"]?>
		<td><?=$row["departement"]?>
		<td><?=$row["CodePostal"]?> <?=$row["Ville"]?>
		<td><?=htmlspecialchars( $row["Association"] )?>
		<td><?=htmlspecialchars( $row["Nom"] . ' ' . $row["Prenom"])?>
		<td class="email"><?=$row["Email"]?>
		<td><?=$row["Adhesion"]?>
		<td><?=htmlspecialchars( $row["Type"] )?>
		<td><?php if($row["Descriptif"] != null)
	 	if( strlen($row["Descriptif"]) > 64) {
	 	 	 echo htmlspecialchars( substr($row["Descriptif"], 0, 40) );
	 	 	 echo ' <a href="" onclick="$(this).parent().html(\''. htmlspecialchars( str_replace( "'", "\\'", str_replace('"', '&quot;', $row["Descriptif"]))) . '\'); return false;"><i> ... (lire la suite)</i></a>';
	 	} else 
	 	 	 echo htmlspecialchars( $row["Descriptif"] );
	 	?>
		<td><a href="<?=$row["SiteWeb"]?>" target="_blank"><?=$row["SiteWeb"]?></a>
		</tr><?php
	}
	?></tbody>
	<tfoot><tr><td colspan="99"></tr>
	</tfoot>
</table>
</form>
<style>
	#<?=$uid?> table thead tr.filters th {
		vertical-align : bottom;
	}
	#<?=$uid?> table thead select {
		height: 8em;
	}
	#<?=$uid?> table tbody {
		background-color: white;
	}
	#<?=$uid?> table tbody tr:hover {
		background-color: #FBFBFB;
	}
	#<?=$uid?> table tbody td {
		border: 1px solid #F0F0F0;
	}
	#<?=$uid?>-emails {
		margin-left: 4em;
		height: 2em;
		width: 30em;
	}
</style>
<script>
	function refreshEmails(){
		var emails = "";
		$("#<?=$uid?> tbody input:checked").each(function(){
			emails += ";" + $(this).parents("tr:first").find('.email').text().trim();
		});
		$("#<?=$uid?>-emails").val(emails.substr(1)).select();
	}
	$().ready(function(){
		$('#<?=$uid?> tbody input[type="checkbox"]')
			.click( refreshEmails )
		;
		refreshEmails();
		
		$('#<?=$uid?>-emails-selector').click(function(){
			if(this.checked)
				$('#<?=$uid?> tbody input[type="checkbox"]').attr({'checked': 'checked'});
			else $('#<?=$uid?> tbody input[type="checkbox"]').removeAttr('checked');
		});
	});
</script>
<?= isset($view) ? $view->searchScript($uid) : '$view no set'?>