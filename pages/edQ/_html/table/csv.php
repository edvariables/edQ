<?php
if(!user_right())
	die('Accès réservé');
$dir = preg_replace('/\.php$/', '', __FILE__);
$file = helpers::combine($dir, "data.csv");

/* réception d'un nouveau fichier */
if(isset($_FILES) && isset($_FILES['q--filename'])){
	if(file_exists($file))
		unlink($file);
	if(!file_exists($dir))
		mkdir($dir);
	if(!move_uploaded_file($_FILES['q--filename']['tmp_name'], $file))
		die('Erreur de copie !');
}

/* formulaire de réception d'un nouveau fichier */
$uid = uniqid('form');
?>
<form id="<?=$uid?>" method="POST" enctype="multipart/form-data"
	  action="<?=page::url( $node )?>"
	  autocomplete="off" style="margin-bottom: 1em;">
	<fieldset><legend>Chargement du fichier</legend>
	<input type="file" name="q--filename"/>
	<input type="submit" value="Envoyer" style="margin-left: 4em;"/>
	</fieldset>
</form>
<?= page::form_submit_script($uidform) ?>

<?php /* affichage des données */
if(file_exists($file))
	$data = file_get_contents($file);
else
	$data = '';
$row_separ = '/\r?\n/';
$column_separ = ';';
$data = preg_split($row_separ, $data);

$columns = array();
$columns[] = array(
	'id' => 'Journal'
);
$columns[] = array(
	'id' => 'Date'
	, 'type' => 'date'
);
$columns[] = array(
	'id' => '_1'
);
$columns[] = array(
	'id' => 'Compte'
);
$columns[] = array(
	'id' => '_2'
);
$columns[] = array(
	'id' => 'Libellé'
);
$columns[] = array(
	'id' => 'Débit'
	, 'type' => 'float'
);
$columns[] = array(
	'id' => 'Crédit'
	, 'type' => 'float'
);
	
?><form>
	<fieldset><legend>Données</legend>
<table class="edq">
<caption><?php
	//bouton de recherche
	/*?><input type="submit" value="Rechercher" style="margin-left: 2em;"/><?php*/
	//lien de téléchargement
	$viewer = tree::get_id_by_name('/_Exemples/Convertisseurs/table/csv');
	$viewer_options = "&node=" . $node['id']
	. "&file--name=" . urlencode($node['nm'])
	. "&node--get=html";
	?><a class="file-download" href="view.php?id=<?=$viewer?><?=$viewer_options?>&vw=file.call" style="margin-left: 2em;">télécharger</a>
</caption>
<thead><tr><?php
	$nCol = 0;
	foreach($columns as $column){
		echo('<th>');
		echo htmlspecialchars(isset($column['text']) ? $column['text'] : $column['id'] );
		echo('</th>');
		++$nCol;
	}
?></tr></thead>
<tbody>
<?php
	foreach($data as $row)
	if($row != null){
		echo('<tr>');
		$row = explode($column_separ, $row); 
		$nCol = 0;
		foreach($row as $cell){
			echo('<td>');
			switch(@$columns[$nCol]['type']){
			case 'float':
				echo $cell;
				break;
			default:
				echo $cell;
				break;
			}
			echo('</td>');
			++$nCol;
		}
		echo('</tr>');
	}
?>
</tbody>
</table>
</fieldset></form>
	