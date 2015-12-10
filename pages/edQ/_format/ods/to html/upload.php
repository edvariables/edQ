<?php
$node = node($node, __FILE__);

if(!isset($arguments))
	return '$arguments manquant';

$file = $arguments['file'];
/* réception d'un nouveau fichier */
if(isset($_FILES) && isset($_FILES['q--filename'])){
	if(file_exists($file))
		unlink($file);
	if(!move_uploaded_file($_FILES['q--filename']['tmp_name'], $file))
		echo("<br>Erreur de copie vers $file !<br>");
	else
		echo "<br>Fichier $file copié<br>";
	$resetCache = true;
}
else 
	$resetCache = isset($_POST) && isset($_POST['q--cache-reset'])
		|| isset($arguments) && isset($arguments['cache-reset']);
if($resetCache && $file){
	node('..', $node, 'call');
	$ods = new ods($file);
	$ods->clearCache();
}
$submit_node = $arguments['submit-node'] ? $arguments['submit-node'] : $node;
$uid = uniqid('form');
?>
<form id="<?=$uid?>" method="POST" enctype="multipart/form-data"
	  action="<?=page::url( $submit_node )?>"
	  autocomplete="off" style="margin-bottom: 1em;">
	<fieldset><legend>Chargement du fichier de tableur (<?=basename($file)?>, <?=file_exists($file) ? 'existe' : 'n\'existe pas'?>)</legend>
	<input type="file" name="q--filename"/>
	<input type="hidden" name="q--cache-reset" value="1"/>
	&nbsp;&nbsp;<input type="submit" value="Envoyer" style="margin-left: 4em;"/>
	</fieldset>
</form>
<?= page::form_submit_script($uid)?>