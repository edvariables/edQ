<?php
helpers::need_plugin('dataTables');
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
	$ulvls = Node::get_ulvls();
	
	$uid = uniqid('form-');
?><table id="<?=$uid?>" class="display" cellspacing="0" width="100%">
	<caption>Utilisateurs
		<div class="edq-toolbar">
			<a href="" class="add-user edq-add">créer un nouvel utilisateur</a>
		</div></caption>
	<thead><tr>
	<th>#</th>
	<th>Nom</th>
	<th>Email</th>
	<th>Téléphone(s)</th>
	<th>Type</th>
	<th>Actif</th>
	</thead>
	<tfoot><tr>
	<th>#</th>
	<th>Nom</th>
	<th>Email</th>
	<th>Téléphone(s)</th>
	<th>Type</th>
	<th>Actif</th>
	</tfoot>
	<tbody><?php
	foreach($rows as $row){
		?><tr iduser="<?=$row["IdContact"]?>">
	 	 <td><?=$row["IdContact"]?></td>
		<td><?=$row["Name"]?>
		<td><?=$row["EMail"]?>
		<td><?=$row["Phone1"]?><?=$row["Phone2"] == null || $row["Phone2"] == '' ? '' : '<br/>' . $row["Phone2"]?>
		<td><?=@$ulvls[$row["UserType"]]?>
		<td><?=$row['Enabled'] ? 'oui' : 'non'?>
		</tr><?php
	}
	?></tbody>
</table>
<style>
#<?=$uid?> tbody tr {
	cursor: pointer;
}
#<?=$uid?> .edq-toolbar {
	float: right;
}
</style>
<script>
$(document).ready(function() {
    $('#<?=$uid?>').dataTable( {
            "language": {
                "url": "res/jquery/dataTables/lang/dataTables.french.json"
            }
			, "iDisplayLength": 50
        } );
	} );
 
	<?php
	/* click sur une ligne pour l'édition d'une fiche */
	$viewerid = node('Edition', $node, 'id');
	?>
    $('#<?=$uid?> tbody').on( 'click', 'tr', function () {
        var tr = $(this);
 		var href = 'view.php?id=<?=$viewerid?>&f--IdContact=' + tr.attr('iduser');
        $.get(href, function(html){
			$('<div></div>').appendTo('body').html(html).dialog({
				title: 'Utilisateur #' + tr.attr('iduser'),
				width: 'auto',
				height: 'auto'
			});
		});
    } );
	<?php
	/* click sur 'nouveau' */
	?>
    $('#<?=$uid?>').on( 'click', '.add-user', function () {
        var button = $(this);
 		var href = 'view.php?id=<?=$viewerid?>&f--IdContact=new';
        $.get(href, function(html){
			$('<div></div>').appendTo('body').html(html).dialog({
				title: 'Nouvel utilisateur',
				width: 'auto',
				height: 'auto'
			});
		});
		return false;
    } );
</script>