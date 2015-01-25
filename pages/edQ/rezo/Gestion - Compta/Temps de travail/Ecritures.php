<?php
if(!isset($node))
	$node = page::node(null);

$arguments_isset = isset($arguments);
$arguments = array_merge($_REQUEST, $arguments_isset ? $arguments : array());

if(user_right())
	$file = preg_replace('/\.php$/', '', __FILE__) . "/csv.php";

/* réception d'un nouveau fichier */
if(isset($_FILES) && isset($_FILES['q--filename'])){
	if(file_exists($file))
		unlink($file);
	if(!move_uploaded_file($_FILES['q--filename']['tmp_name'], $file))
		die('Erreur de copie !');
}

?>
<form><fieldset><legend>Transformation des écritures de l'expert-comptable pour Cogilog</legend>
<pre>- Etape 1 : Importer ici le fichier de l'expert-comptable (Parcourir... puis Envoyer)
- Etape 2 : Contrôler que les données calculées sont cohérentes
- Etape 3 : Télécharger le fichier pour Cogilog
- Etape 4 : Copier le fichier depuis les Téléchargements vers le dossier partagé de Soleil
- Etape 5 : Importer dans Cogilog Compta</pre></fieldset></form>
<?php
	
// $columns
$columns[] = array(
	'id' => 'Journal'
);
$columns[] = array(
	'id' => 'Date'
	, 'type' => 'date'
);
$nDate = count($columns) - 1;
$columns[] = array(
	'id' => '(vide)'
);
$columns[] = array(
	'id' => 'Compte'
);
$nCompte = count($columns) - 1;
$columns[] = array(
	'id' => 'Analytique'
);
$nAnalytique = count($columns) - 1;
$columns[] = array(
	'id' => 'Libellé'
);
$nLibelle = count($columns) - 1;
$columns[] = array(
	'id' => 'Débit'
	, 'type' => 'double'
);
$nDebit = count($columns) - 1;
$columns[] = array(
	'id' => 'Crédit'
	, 'type' => 'double'
);
$nCredit = count($columns) - 1;
	
/* formulaire de réception d'un nouveau fichier */
$uid = uniqid('form');
?>
<form id="<?=$uid?>" method="POST" enctype="multipart/form-data"
	  action="<?=page::url( $node )?>"
	  autocomplete="off" style="margin-bottom: 1em;">
	<fieldset><legend>Etape 1 : Chargement du fichier mensuel en provenance de l'expert-comptable</legend>
		- le fichier devrait se trouver dans <i>/docCompta/AnnieBate/Administration - Compta/Paies Rezo/Payes et cotisations/[année]/Ecritures et Journaux de payes</i>
		<br/>
		- colonnes : <?php
		$nCol = 0;
		foreach($columns as $column){
			if($nCol++ > 0) echo ', ';
			echo($column['id']);
		}
	?>
		<br/>
		- séparateur : {tab} ou ; (point-virgule)
		<br/><br/>
	<input type="file" name="q--filename"/>
	<input type="submit" value="Envoyer" style="margin-left: 4em;"/>
	</fieldset>
</form>
<?= isset($view) ? $view->searchScript($uid) : '$view no set'?>

<?php 
	
/* chargement des données csv */
$rows = file_get_contents($file);

$row_separ = '/\r?\n/';
$column_separ = '/[\t;]/';
$rows = preg_split($row_separ, $rows);
$nRow = 0;
foreach($rows as $row)
	if(($row != null)
	   && (substr($row, 0, 2) != '**')) {
		$row = preg_split($column_separ, $row); 
		if($row[$nDebit] == '0')
			$row[$nDebit] = '';
		else if($row[$nDebit])
			$row[$nDebit] = floatval (str_replace(' ', '', str_replace(',', '.', $row[$nDebit])));
		if($row[$nCredit] == '0')
			$row[$nCredit] = '';
		else if($row[$nCredit])
			$row[$nCredit] = floatval (str_replace(' ', '', str_replace(',', '.', $row[$nCredit])));
		$rows[$nRow++] = $row;
	}

/*var_dump($columns);
var_dump($rows);*/

$salairesPayes = 0;
$datePayes = null;
$datePayesFin = null;
define('CPTE_SALAIRES', '641100');
foreach($rows as $row)
	if($row[$nCompte] == CPTE_SALAIRES){
		$salairesPayes = floatval (str_replace(' ', '', str_replace(',', '.', $row[$nDebit])));
		$datePayes = DateTime::createFromFormat('d/m/Y H:i:s', $row[$nDate] . ' 00:00:00');
		if(intval($datePayes->format('Y')) < 2000)
			$datePayes->add(new DateInterval('P2000Y'));
		$datePayes->sub(new DateInterval('P' . ((int)$datePayes->format('d') - 1) . 'D'));
		$datePayesFin = clone $datePayes;
		$datePayesFin->add(new DateInterval('P1M'));
		$datePayesFin->sub(new DateInterval('PT1S'));
		break;
	}

// Données de synthèse
$args = array(
	'node--get' => 'rows'
	, 'q--date-debut' => $datePayes
	, 'q--date-fin' => $datePayesFin
);

page::call('Analytique/Synthese', $args, __FILE__);
$salaries = $args['rows'];

/*$args = array(
	'rows' => $salaries
);
page::call('/_html/table/rows', $args, __FILE__);*/


//Comparaison des totaux des salaires
/*$salairesDeclares = 0;
$salairesEcart = 0;
define('COL_TOTAL', '(total)');
for($nRow = 0; $nRow < count($salaries); $nRow++)
	if($salaries[$nRow]['CodeAnalytique'] == COL_TOTAL){
		$salairesDeclares = $salaries[$nRow]['Valorisation'];
		$salairesEcart = $salairesDeclares - $salairesPayes;
		echo('<pre>Ecart sur les salaires : ' . round($salairesDeclares, 2) . " - " . round($salairesPayes, 2) . ' = ' . round($salairesEcart, 2) . "</pre>");
		break;
	}
// recalcul des fractions
for($nRow = 0; $nRow < count($salaries); $nRow++){
	//Affectation de l'écart au code analytique FONG
	if($salaries[$nRow]['CodeAnalytique'] == 'FONG')
		$salaries[$nRow]['Valorisation'] -= $salairesEcart;
	$salaries[$nRow]['Fraction'] = $salaries[$nRow]['Valorisation'] / $salairesPayes * 100;
}*/
/*$args = array(
	'rows' => $salaries
);
page::call('/_html/table/rows', $args, __FILE__);*/

$merge_comptes = isset($arguments['f--merge_comptes']) ? $arguments['f--merge_comptes'] : false;

$nbRows = count($rows);
$rowsCptes = array();
for($nRow = 0; $nRow < $nbRows; $nRow++){
	$row = $rows[$nRow];
	if(is_array($row)){
		$nCol = 0;
		foreach($row as $cell){
			switch(@$columns[$nCol]['type']){
			case 'double':
			case 'float':
				if($cell){
					//echo ">>";var_dump($cell);
					$cell = $row[$nCol] = $rows[$nRow][$nCol] 
						= floatval( str_replace(',', '.', str_replace(' ', '', $cell) ));
					//var_dump($cell); echo "<<";
				}
				break;
			default:
				break;
			}
			++$nCol;
		}
		if(!isset($analytic[$row[$nCompte]]))
			$analytic[$row[$nCompte]] = 0.0;
		//effacement de 0 résiduels
		if($row[$nDebit] && $row[$nCredit])
			if($row[$nDebit] == '0' && $row[$nCredit] != '0')
				$row[$nDebit] = '';
			else
				$row[$nCredit] = '';
			
		// total de la ligne du comptable
		if($row[$nDebit])
			$totalLigne = $row[$nDebit];
		else if($row[$nCredit])
			$totalLigne = $row[$nCredit];
		// cumuls par compte
		$analytic[$row[$nCompte]] += $totalLigne;
		
		//Par compte
		if(!isset($rowsCptes[$row[$nCompte]])){
			$rowsCptes[$row[$nCompte]] = $row;
			if(!$row[$nDebit])
				$rowsCptes[$row[$nCompte]][$nDebit] = 0.0;
			if(!$row[$nCredit])
				$rowsCptes[$row[$nCompte]][$nCredit] = 0.0;
		}
		else if($row[$nDebit])
			$rowsCptes[$row[$nCompte]][$nDebit] += $totalLigne;
		else if($row[$nCredit])
			$rowsCptes[$row[$nCompte]][$nCredit] += $totalLigne;
	}
}

//echo '<pre>'; var_dump($rowsCptes); echo '</pre>'; 
//echo '<pre>'; var_dump($salaries); echo '</pre>'; 

$nbRows = count($rows);
$rowsCSV = array();
for($nRow = 0; $nRow < $nbRows; $nRow++){
	$row = $rows[$nRow];
	if(is_array($row)
	  && isset($rowsCptes[$row[$nCompte]])){
		
		/* une seule ecriture par compte */
		if($merge_comptes){
			$row[$nLibelle] = $rowsCptes[$row[$nCompte]][$nLibelle];
			$row[$nCredit] = $rowsCptes[$row[$nCompte]][$nCredit];
			$row[$nDebit] = $rowsCptes[$row[$nCompte]][$nDebit];
			if($row[$nCredit] > $row[$nDebit]){
				$row[$nCredit] -= $row[$nDebit];
				$row[$nDebit] = '';
			}
			else {
				$row[$nDebit] -= $row[$nCredit];
				$row[$nCredit] = '';
			}
			unset($rowsCptes[$row[$nCompte]]);
		}
		
		$nCol = 0;
		foreach($row as $cell){
			switch(@$columns[$nCol]['type']){
			case 'double':
			case 'float':
				if($cell && is_string($cell)){
					//echo ">>";var_dump($cell);
					$cell = $row[$nCol] = $rows[$nRow][$nCol] 
						= floatval( str_replace(',', '.', str_replace(' ', '', $cell) ));
					//var_dump($cell); echo "<<";
				}
				break;
			default:
				break;
			}
			++$nCol;
		}
		if(!isset($analytic[$row[$nCompte]]))
			$analytic[$row[$nCompte]] = 0.0;
		//effacement de 0 résiduels
		if($row[$nDebit] && $row[$nCredit])
			if($row[$nDebit] == '0' && $row[$nCredit] != '0')
				$row[$nDebit] = '';
			else
				$row[$nCredit] = '';
			
		// total de la ligne du comptable
		if($row[$nDebit])
			$totalLigne = floatval (str_replace(' ', '', str_replace(',', '.', $row[$nDebit])));
		else if($row[$nCredit])
			$totalLigne = floatval (str_replace(' ', '', str_replace(',', '.', $row[$nCredit])));
		
		// Comptes en 6 : crée autant de lignes que de codes analytiques + 1 ligne négative
		if(substr($row[$nCompte], 0, 1) == '6'){
			$sum = 0.0;
			$sum2dec = 0.0; // sum sur la base de deux décimales
			foreach($salaries as $salarie){
				//1 ligne négative
				if($salarie['CodeAnalytique'] == COL_TOTAL){
					$newRow = array();
					$nCol = 0;
					foreach($row as $cell){
						if(($nCol == $nDebit) || ($nCol == $nCredit)){
							$nColInv = $nCol == $nDebit ? $nCredit : $nDebit;
							if($row[$nColInv]){
								$newRow[] = $totalLigne;
							}
							else
								$newRow[] = '';
						}
						else
						   	$newRow[] = $cell;
						++$nCol;
					}
					$rows[] = $newRow;
				}
				else { //1 ligne par code analytique
					$newRow = array();
					$nCol = 0;
					foreach($row as $cell){
						if(($nCol == $nDebit) || ($nCol == $nCredit)){
							if(is_numeric($row[$nCol]) && $row[$nCol] != 0){
								$fract = $totalLigne * $salarie['Fraction'] / 100;
								$newRow[] = $fract;
								$sum += $fract;// * ($nCol == $nDebit ? -1 : 1); 
								$sum2dec += round($fract, 2);
								//echo '<br>' . $row[$nCompte] . ", col " . $nCol . ' = ' . $fract . ' = ' . $sum2dec;
							}
							else
								$newRow[] = '';
						}
						else if($nCol == $nLibelle){
						  $newRow[] = $cell . " - " . ' ' . number_format( $salarie['Fraction'] , 2, ',', '') . ' % ' . $salarie['CodeAnalytique'];
						}
						else if($nCol == $nAnalytique){
						  $newRow[] = $salarie['CodeAnalytique'];
						}
						else
						   	$newRow[] = $cell;
						++$nCol;
					}
					$rows[] = $newRow;
					$rowsCSV[] = $newRow;
				}
			}
			if($sum2dec && (abs($sum2dec - $totalLigne) >= 0.001)){
				// écart d'arrondi : ajouté à la dernière ligne histoire de solder à 0
				if(is_numeric($newRow[$nDebit]) && ($newRow[$nDebit] != 0))
					$nCol = $nDebit;
				else
					$nCol = $nCredit;
				$rows[ count($rows) - 1 ][$nCol] -= ($sum2dec - $totalLigne);
				$rowsCSV[ count($rowsCSV) - 1 ][$nCol] -= round(($sum2dec - $totalLigne) * 100) / 100;
				/*echo('<pre>');
				echo "Attention, compte " . $row[$nCompte] . " : Ecart entre la ligne d'origine (" . $totalLigne . ") et la répartition par code analytique (" . $sum2dec . ") : "
						. number_format($sum2dec - $totalLigne, 3, ',', '');
				echo('</pre>');*/
			}
		}
		else
			$rowsCSV[] = $row;
		
	}
}

if($arguments_isset && $arguments['node--get'] == 'rows'){
	$arguments['rows'] = $rowsCSV;
	$arguments['columns'] = $columns;
	return;
}

/* résultat */
$uid = uniqid('form');
?>
<form id="<?=$uid?>" method="POST" action="<?=page::url( $node )?>"
	  autocomplete="off" style="margin-bottom: 1em;">
<?= isset($view) ? $view->searchScript($uid) : '$view no set'?>
	<fieldset><legend>Etapes 2 et 3 : Comptes en 6 éclatés par code analytique.</legend>
		<label>fusionner les comptes identiques</label>
		<label><input type="radio" name="f--merge_comptes"<?= $merge_comptes ? ' checked="checked"' : ''?> value="1"/>oui</label>
		<lablel><input type="radio" name="f--merge_comptes"<?= !$merge_comptes ? ' checked="checked"' : ''?> value=""/>non</label>
		
		<input type="submit" value="Recharger" style="margin-left: 2em;"/><?php 
	$viewer = tree::get_id_by_name('/_Exemples/Convertisseurs/table/csv');
		$viewer_options = "&node=" . $node['id']
		. "&file--name=" . $node['nm']
		. "&node--get=" . 'rows';
		?><a class="file-download" href="view.php?id=<?=$viewer?><?=$viewer_options?>&vw=file.call" style="margin-left: 2em;">télécharger le .csv</a>
	<?php
		$cogilog = page::node(':Cogilog', $node);
		$file_cogilog = 'EA_' . $datePayes->format('m_Y') . '.csv';
		$viewer_options = "&node=" . $cogilog['id']
		. "&file--name=" . $file_cogilog
		. "&node--get=" . 'rows';
		?><a class="file-download" href="view.php?id=<?=$viewer?><?=$viewer_options?>&vw=file.call" style="margin-left: 2em;"
			 title="Le format est : colonne Point-virgule, UTF8, pas de guillemet autour des champs"
											   >Etape 3 : télécharger <?=$file_cogilog?></a>
	<?php
	$args = array(
		'rows' => $rowsCSV
		,'columns' => $columns
		, 'csv--node' => false

	);
	page::call('/_html/table/rows', $args, __FILE__);?>

		<?=count($args['rows'])?> ligne(s)
	</fieldset></form>

<script>
	$().ready(function(){
		//au début du click sur le téléchargement, on ajoute f--merge_comptes au href
		$('#<?=$uid?> .file-download').mousedown(function(){
			$this = $(this);
			$form = $this.parents('form:first');
			var href = $this.attr('href');
			var params = ['f--merge_comptes'];
			for(var param in params){
				param = params[param];
				var pos = href.indexOf('&' + param + '=');
				var $input = $form.find(':input[name="' + param + '"]');
				if($input.length > 1 && $input.attr('type') == 'radio') $input = $input.filter(':checked'); 
				var value = $input.val();
				if(pos > 0){
					var posNext = href.indexOf('&', pos + 2);
					href = href.substr(0, pos)
						+ '&' + param + '=' + value
						+ (posNext > 0 ? href.substr(posNext) : '')
					;
				}
				else
					href += '&' + param + '=' + value;
			}
			$this.attr('href', href);
		});
	});
</script>
<pre><?php echo str_replace(',', ',<br>', json_encode($analytic))?></pre>
	