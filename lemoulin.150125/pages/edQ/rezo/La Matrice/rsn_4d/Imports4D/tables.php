<?php

if(isset($arguments) && isset($arguments['files-path']))
   $files_path = $arguments['files-path'];
else
	$files_path = '/Users/cogi4d/Desktop/Exports Tables 4D';

$tables = array(
	"adresse" => array(
		'label' => "Adresses",
		'file' => "Adresse",
	),

	"liensadresses" => array(
		'label' => "Liens entre adresses",
		'file' => "LiensAdresses",
	),

	"groupe" => array(
		'label' => "Groupes",
		'file' => "Groupe",
	),
	"don_adr" => array(
		'label' => "Dons",
		'file' => "Dons_Adr",
	),
	"lignefacturecogilog" => array(
		'label' => "Lignes de facture Cogilog",
		'file' => "LigneFactureCogilog",
	),
	"courrier"=> array(
		'label' => "Courriers",
		'file' => "Courrier",
	),
	"critere"=> array(
		'label' => "Critères",
		'file' => "Critères",
	),
	"critere_adr"=> array(
		'label' => "Affectations de critères",
		'file' => "Critères_Adr",
	),
	"prelevement"=> array(
		'label' => "Prélèvements",
		'file' => "Prélèvements",
	),
	"prlv histo details"=> array(
		'label' => "Ordres de prélèvement",
		'file' => "Prélèvements_HistoDétail",
	),
	"inscr donateurs web"=> array(
		'label' => "Inscr. Donateurs Web",
		'file' => "InscriptionDonateurWeb",
	),
	"liens4d donateurs web"=> array(
		'label' => "Liens 4D Donateurs Web",
		'file' => "LienRef4DdonateursWeb",
	),
	"abreviationsposte"=> array(
		'label' => "Abréviations de la Poste",
		'file' => "LaPosteAbréviations",
	),
	"codepostal"=> array(
		'label' => "Codes postaux",
		'file' => "stat_CP",
	),
	"departement"=> array(
		'label' => "Départements",
		'file' => "Département",
	),
	"pays"=> array(
		'label' => "Pays",
		'file' => "Pays",
	),
	"depute"=> array(
		'label' => "Députés",
		'file' => "Député",
	),
	"emaildomains"=> array(
		'label' => "Domaines d'emails",
		'file' => "Domaines",
	),
);

foreach($tables as $node=>$table){
	$file_root = helpers::combine($files_path, $table['file']);
	for($nFile = 0; $nFile < 32; $nFile++) {
		$file = $file_root . ($nFile === 0 ? '' : '-' . $nFile) . '.csv';
		if(!file_exists( utf8_decode( $file ) ))
			break;
		if($nFile === 0)
			$date_min = filemtime( utf8_decode( $file ));
		else if($date_min > filemtime( utf8_decode( $file )))
			continue;
			
		$tables[$node]['files'] = $nFile + 1;
		if($nFile === 0)
			$tables[$node]['files-date'] = date('d/m/Y H:i:s', $date_min);
	}
}
/*
echo '<pre>';
var_dump($tables);
echo '</pre>';*/

return $tables;
?>