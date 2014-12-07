<?php
$db = get_db();


$search = 0;
if(isset($_REQUEST["d--IdContact"])){
	$search = (int)$_REQUEST["d--IdContact"];
	$isCurrentUser = $_SESSION['edq-user']['id'] == $search;
}
else if(isset($_REQUEST["f--IdContact"])){
	$search = (int)$_REQUEST["f--IdContact"];
	$isCurrentUser = $_SESSION['edq-user']['id'] == $search;
}
else {
	$search = $_SESSION['edq-user']['id']; //exemple
	$isCurrentUser = true;
}
if($search == '0' || $search == 'new'){
	$rows = array(array(
		'IdContact' => 'new'
		, 'Enabled' => '1'
		, 'UserType' => '64'
	));
	$search = false;
}
else {
	$rows = $db->all("
		SELECT c.IdContact, c.Name, c.ShortName, c.Email, c.Phone1
		, u.Enabled, u.UserType
		FROM contact c
		JOIN user u
			ON c.IdContact = u.IdUser
		WHERE
			c.IdContact = ?
		LIMIT 1"
		, array( $search )
	);
}
$uid = uniqid('form-');

$node = node($node);

if(count($rows) > 0){
	$row = $rows[0];
	$currentUserType = $_SESSION['edq-user']['UserType'];
	
?><form id="<?=$uid?>" method="POST" action="<?=page::url( ":submit", $node )?>" autocomplete="off">
<input type="hidden" name="op" value="<?=$row['IdContact'] ? 'update' : 'insert'?>"/>
<input type="hidden" name="d--IdContact" value="<?=$row['IdContact']?>"/>
<fieldset class="q-fields">	<legend><?=$search ? ($row["Name"] . '#' . $row["IdContact"]) : 'Nouvel utilisateur'?></legend>
<div>
	<div><label class="ui-state-default ui-corner-all">Nom</label>
	<input size="40" name="d--Name" value="<?= htmlentities($row['Name']) ?>"/>
	<input size="3" name="d--ShortName" value="<?= htmlentities($row['ShortName']) ?>"/>
	</div>

	<div><label class="ui-state-default ui-corner-all">Email</label>
	<input size="40" name="d--EMail" value="<?= htmlentities($row['EMail']) ?>"/>
	</div>
	<div><label class="ui-state-default ui-corner-all">Téléphone</label>
	<input size="40" name="d--Phone1" value="<?= htmlentities($row['Phone1']) ?>"/>
	</div>

	<div><label class="ui-state-default ui-corner-all">Utilisateur <?= $row['Enabled'] ? 'actif' : 'désactivé' ?></label>
	<label>
		<input type="radio" name="d--user-Enabled" value="1" <?=$isCurrentUser ? ' disabled="disabled"' : ''?>
			   <?= $row['Enabled'] ? ' checked="checked"' : '' ?>/>Actif</label>
	<label>
		<input type="radio" name="d--user-Enabled" value="0" <?=$isCurrentUser ? ' disabled="disabled"' : ''?>
			   <?= $row['Enabled'] ? '' : ' checked="checked"' ?>/>Désactivé</label>
	</div>

	<div><label class="ui-state-default ui-corner-all">Niveau d'utilisateur</label>
	<?php // ulvl
	$ulvls = Node::get_ulvls();
	?><select name="d--user-UserType" <?=$isCurrentUser ? ' disabled="disabled"' : ''?> value="<?= ifNull($row['UserType'], '1024')?>"><?php
	foreach($ulvls as $ulvl => $text)
	if($currentUserType <= $ulvl) {
		?><option value="<?= $ulvl ?>"
		<?= $row['UserType'] == $ulvl ? ' selected="selected"' : ''?>
		><?= htmlspecialchars ($text) ?>
		</option><?php
	}
	?></select>
	</div>	
	
	<div><label class="ui-state-default ui-corner-all">Mot de passe</label>
	<input type="password" size="40" name="d--user-Password" value=""/>
	<br><input type="password" size="40" name="d--user-Password-confirm" value="" title="Confirmation du mot de passe"/></div>
</div></fieldset>
<fieldset>
	<input type="submit" value="Enregistrer"/>
	<div class="edq-toolbar">
	<?php
	if($search && !$isCurrentUser && $currentUserType < $row['UserType']){
		?>
		<a href="" class="edq-delete">supprimer</a><?php
	}?></div>
</fieldset>
</form>
<style>
	#<?=$uid?> .edq-toolbar {
		float: right;
	}
</style>
<?php
	echo page::form_submit_script($uid, array( 
		'beforeSubmit' => '
			var $form = $("#' . $uid . '"),
				$pwd = $form.find(\'input[name="d--user-Password"]\'),
				$pwd_confirm = $form.find(\'input[name="d--user-Password-confirm"]\');
			if($pwd.val() && $pwd.val() != $pwd_confirm.val()){
			 	alert("La confirmation du mot de passe n\'est pas correcte.");
				return false;
			}
		'
	));
}
else {
	echo $search . ' <i> introuvable</i>';
}?>