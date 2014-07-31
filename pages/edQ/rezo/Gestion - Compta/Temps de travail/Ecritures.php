<?php
ini_set( "display_errors", 1);
error_reporting (E_ALL & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED);

$arguments_isset = isset($arguments);
$arguments = array_merge($_REQUEST, $arguments_isset ? $arguments : array());

if(userRight())
	$file = preg_replace('/\.php$/', '', __FILE__) . "/csv.php";

/* réception d'un nouveau fichier */
if(isset($_FILES) && isset($_FILES['q--filename'])){
	if(file_exists($file))
		unlink($file);
	if(!move_uploaded_file($_FILES['q--filename']['tmp_name'], $file))
		die('Erreur de copie !');
}

/* formulaire de réception d'un nouveau fichier */
$uid = uniqid('form');
?>
<form id="<?=$uid?>" method="POST" enctype="multipart/form-data"
	  action="<?=url_view( $node )?>"
	  autocomplete="off" style="margin-bottom: 1em;">
	<fieldset><legend>Chargement du fichier mensuel en provenance de l'expert-comptable</legend>
	<input type="file" name="q--filename"/>
	<input type="submit" value="Envoyer" style="margin-left: 4em;"/>
	</fieldset>
</form>
<?= isset($view) ? $view->searchScript($uid) : '$view no set'?>

<?php /* affichage des données */
$rows = file_get_contents($file);

$row_separ = '/\r?\n/';
$column_separ = ';';
$rows = preg_split($row_separ, $rows);
$nRow = 0;
foreach($rows as $row)
	if($row != null)
		$rows[$nRow++] = explode($column_separ, $row); 

// $columns
$columns = array();
$columns[] = array(
	'id' => 'Journal'
);
$columns[] = array(
	'id' => 'Date'
	, 'type' => 'date'
);
$nDate = count($columns) - 1;
$columns[] = array(
	'id' => '_1'
);
$columns[] = array(
	'id' => 'Compte'
);
$nCompte = count($columns) - 1;
$columns[] = array(
	'id' => 'Analityque'
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


$salairesPayes = 0;
$datePayes = null;
$datePayesFin = null;
define('CPTE_SALAIRES', '641100');
foreach($rows as $row)
	if($row[$nCompte] == CPTE_SALAIRES){
		$salairesPayes = floatval (str_replace(' ', '', str_replace(',', '.', $row[$nDebit])));
		$datePayes = DateTime::createFromFormat('d/m/Y H:i:s', $row[$nDate] . ' 00:00:00');
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

call_page('Analytique/Synthese', $args, __FILE__);
$salaries = $args['rows'];

/*$args = array(
	'rows' => $salaries
);
call_page('/edQ/_Exemples/html/table/rows', $args, __FILE__);*/

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
call_page('/edQ/_Exemples/html/table/rows', $args, __FILE__);*/

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
			$rowsCptes[$row[$nCompte]] = array_slice ( $row, 0);
			if(!$row[$nDebit])
				$rowsCptes[$row[$nCompte]][$columns[$nDebit]['id']] = 0.0;
			if(!$row[$nCredit])
				$rowsCptes[$row[$nCompte]][$columns[$nCredit]['id']] = 0.0;
		}
		else if($row[$nDebit])
				$rowsCptes[$row[$nCompte]][$columns[$nDebit]['id']] += $totalLigne;
		else if(!$row[$nCredit])
				$rowsCptes[$row[$nCompte]][$columns[$nCredit]['id']] += $totalLigne;
	}
}

$nbRows = count($rows);
$rowsCSV = array();
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
			$totalLigne = floatval (str_replace(' ', '', str_replace(',', '.', $row[$nDebit])));
		else if($row[$nCredit])
			$totalLigne = floatval (str_replace(' ', '', str_replace(',', '.', $row[$nCredit])));
		// cumuls par compte
		$analytic[$row[$nCompte]] += $totalLigne;
		
		// Comptes en 6 : crée autant de lignes que de codes analytiques + 1 ligne négative
		if(substr($row[$nCompte], 0, 1) == '6'){
			$sum = 0.0;
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
							if($row[$nCol]){
								$fract = $totalLigne * $salarie['Fraction'] / 100;
								$newRow[] = $fract;
								$sum += $fract;
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
			if(abs($sum - $totalLigne) > 0.004){
				echo('<pre>');
				echo "Attention : Ecart entre la ligne d'origine et la répartition par code analytique : " . number_format($sum - $totalLigne, 3, ',', '');
				echo('</pre>');
			}
		}
		else
			$rowsCSV[] = $row;
		
	}
}

if($arguments_isset && $arguments['node--get'] == 'rows'){
	$arguments['rows'] = $rowsCSV;
	return;
}
?>

<form>
	<fieldset><legend>Données en provenance de l'expert-comptable</legend>
<?php
$args = array(
	'rows' => $rowsCSV
	,'columns' => $columns
	, 'csv--node' => $node['id']
	, 'csv--file' => $node['nm']
	, 'csv--rows' => 'rows'
		
);
call_page('/edQ/_Exemples/html/table/rows', $args, __FILE__);?>
</fieldset></form>
<pre><?php var_dump($analytic)?></pre>
	