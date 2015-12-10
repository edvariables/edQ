<?php
$node = node($node, __FILE__);
$uid = uniqid('form');

$parentNode = node('..', $node);
$prodName =  preg_replace('/\.dev$/', '', $parentNode['nm']);
$devNode = node('..' . $prodName . '.dev', $node);
$prodNode = node('..' . $prodName, $node);

//submited actioin
if(isset($_POST)
   && isset($_POST['q-direction'])){
	$direction = $_POST['q-direction'];
	$from = false;
	switch($direction){
	case 'from_dev_to_prod':
		$fromDev = true;
		$from = $devNode;
		$to = $prodNode;
		break;
	case 'from_prod_to_dev':
		$fromDev = false;
		$from = $prodNode;
		$to = $devNode;
		break;
	default:
		?><code>Erreur : direction '<?=$direction?>' inconnue.</code><?php
		break;
	}
	if($from){
		$fromFile = node($from, false, 'file');
		$toFile = node($to, false, 'file');
		?><h2>Copie <?=$fromDev ? " du développement" : " de la production"?> vers <?=$fromDev ? "la production" : "le développement"?> </h2><?php
		?><ul><li>Source : <?=dirname($fromFile)?>/<b><?=basename($fromFile, '.php')?></b>
		<li>Destination : <?=dirname($toFile)?>/<b><?=basename($toFile, '.php')?></b>
		</ul><?php
		try {
			copy($fromFile, $toFile);
			
			$fromFile = dirname($fromFile) . '/' . basename($fromFile, '.php');
			$toFile = dirname($toFile) . '/' . basename($toFile, '.php');
			helpers::rcopy($fromFile, $toFile);
			
			?><br><i>copie effectuée</i><?php
		}
		catch(Exception $ex){
			?><br><h2><pre>Erreur : <?=$ex?></pre></h2><?php
		}
	}
}
?>
<form id="<?=$uid?>" method="POST" action="<?=page::url( $node )?>" autocomplete="off">
	<caption><h2>Synchronisation de <var><?=$prodName?></var></h2>
		<h3>Attention : La synchronisation supprime tout le répertoire de destination avant copie de la source</h3></caption>
	<label><input type="radio" name="q-direction" value="from_dev_to_prod">Copier cette version de développement <b>vers la production</b></label>
	<br>
	<label><input type="radio" name="q-direction" value="from_prod_to_dev">Copier cette version de production <b>vers le développement</b></label>
	<br><br>
	<input type="submit" value="Exécuter"/>
</form>
<?= page::form_submit_script($uid)?>
<?php
?>