<?php
ini_set( "display_errors", 1);
error_reporting (E_ALL & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED);

$arguments = array_merge($_REQUEST, isset($arguments) ? $arguments : array());

$dateDebut = isset($arguments['q--date-debut'])
	? $arguments['q--date-debut']
	: '01/09/' . ((int)date('m') > 8 ? date('Y') : (int)date('Y') - 1);
$dateTimeDebut = is_string($dateDebut) ? DateTime::createFromFormat('d/m/Y H:i:s', $dateDebut. '00:00:00') : $dateDebut;
$params[] = $dateTimeDebut->format('Y-m-d H:i:s');

$dateFin = isset($arguments['q--date-fin'])
	? $arguments['q--date-fin']
	: '31/08/' . ((int)date('m') <= 8 ? date('Y') : (int)date('Y') + 1);
$dateTimeFin = is_string($dateFin) ? DateTime::createFromFormat('d/m/Y H:i:s', $dateFin . ' 23:59:59') : $dateFin;
$params[] = $dateTimeFin->format('Y-m-d H:i:s') ;

$args = array(
	'node--get' => 'rows'
	, 'q--date-debut' => $dateTimeDebut
	, 'q--date-fin' => $dateTimeFin
	, 'q--salarie' => 0
	, 'q--limit' => 99999
);

page::call('..', $args, __FILE__);

if(!isset($args['rows']))
	die('Aucune donnée analytique');

$rows = $args['rows'];

define('COL_TOTAL', '(total)');

$moiss = array();
//compile les codes analytiques par mois
foreach($rows as $row){
	$mois = $row['Mois'];
	if(!isset($moiss[$mois]))
		$moiss[$mois] = array( COL_TOTAL => 0 );
	
	if($row['CodeAnalytique'] == ''
	|| $row['CodeAnalytique'] == 'ERR'
	  )
		$row['CodeAnalytique'] = 'FONG';
	
	if(!isset($moiss[$mois][$row['CodeAnalytique']]))
		$moiss[$mois][$row['CodeAnalytique']] = 0;
	//var_dump($row['Valorisation']);
	$moiss[$mois][$row['CodeAnalytique']] += floatval($row['Valorisation']);
	$moiss[$mois][ COL_TOTAL ] += floatval($row['Valorisation']);
}
// fractions mensuelles

$rows = array();
foreach($moiss as $mois => $anals)
	foreach($anals as $anal => $valo)
		$rows[] = array(
			'Mois' => $mois
			, 'CodeAnalytique' => $anal
			, 'Valorisation' => $valo
			, 'Fraction' => $valo / $anals[COL_TOTAL] * 100
		);
//var_dump($rows);
if($arguments['node--get'] == 'rows'){
	$arguments['rows'] = $rows;
	return;
}
?>
<div><?php //lien de téléchargement
$viewer = tree::get_id_by_name('/_Exemples/Convertisseurs/table/csv');
$viewer_options = "&node=" . $node['id']
	. "&file--name=" . urlencode($node['nm'])
	. "&node--get=html";
?><a class="file-download" href="view.php?id=<?=$viewer?><?=$viewer_options?>&vw=file.call" style="margin-left: 2em;">télécharger</a>
</div>

<?php
$args = array(
	'rows' => $rows
	, 'columns' => array(
		'*' => true
		, 'Valorisation' => array(
			'type' => 'float'
		)
	)
);
page::call('/_html/table/rows', $args, __FILE__);

?>