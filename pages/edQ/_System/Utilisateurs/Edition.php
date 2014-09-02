<?php
	 $db = get_db();

	 $search = 0;
	 if(isset($_REQUEST["f--IdContact"]))
	 	 $search = (int)$_REQUEST["f--IdContact"];
	 else
	 	 $search = $_SESSION['edq-user']['id']; //exemple
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

if(count($rows) > 0){
	$row = $rows[0];
?><form id="<?=$uid?>" method="POST" action="<?=page::url( ":submit", $node )?>" autocomplete="off">
<input type="hidden" name="operation" value="<?=$row['IdContact'] ? 'update' : 'insert'?>"/>
<input type="hidden" name="d--IdContact" value="<?=$row['IdContact']?>"/>
<fieldset class="q-fields">	<legend><?=$row["Name"]?> #<?=$row["IdContact"]?></legend>
<div>
	<div><label class="ui-state-default ui-corner-all">Nom</label>
	<input size="40" name="d--Name" value="<?= htmlentities($row['Name']) ?>"/></div>

	<div><label class="ui-state-default ui-corner-all">Mot de passe</label>
	<input type="password" size="40" name="d--user-Password" value=""/>
	<br><input type="password" size="40" name="d--user-Password-confirm" value="" title="Confirmation du mot de passe"/></div>
</div></fieldset>
<fieldset>
	<input type="submit" value="Enregistrer"/>'
</fieldset>
</form>
<style>
</style>
<?php
	echo $view->formScript($uid, null);
}
else {
	echo $search . ' <i> introuvable</i>';
}?>