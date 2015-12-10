<?php /* Conversion de html en fichier .csv
	Les données peuvent être fournies sous forme html.
	Le fournisseur est désigné par la propriété $arguments['node'].
	Ce noeud est appelé en transmettant la variable $arguments.
	Ce noeud fournit les données en retour d'après l'existence de l'argument 'node--get' :
		$arguments['node--get'] = 'rows';
		//il faut, bien sûr, que le noeud qui fournit les données traite lui-même $arguments['node--get'];
		$rows = page::call($arguments['node'], $arguments, __FILE__);
				
	
	Sinon, le noeud retourne du html et les données sont extraites de <table/>. Le code html doit être rigoureux (fermetures de balises).
*/


//echo(' csv entree $arguments : '); var_dump($arguments);
if(!isset($arguments))
	$arguments = array();

$table = isset($arguments['html']) ? $arguments['html'] : false;

if(!$table){
	if(!isset($arguments['q--limit']))
		$arguments['q--limit'] = 99999;

	//node source 
	$nodesource = isset($arguments['node'])
		? $arguments['node']
		: (isset($_REQUEST['node']) ? $_REQUEST['node'] : '/_Exemples/data/table'); 
	if(is_numeric($nodesource)){
		global $tree;
		$nodesource = $tree->get_path_string($nodesource);
	}
	$arguments['node'] = $nodesource;

	/* execution */
	ob_start();

	$rows = page::call($nodesource, $arguments, __FILE__);

	$table = ob_get_clean(); /* la page a retournée le html de la table */

	if(is_array($rows))
		return $rows;
}

require_once('res/simple_html_dom.php');
try{
	$dom = str_get_html($table);//preg_replace('/[\\r\\n\\t]/', '', $table));
}
catch(Exception $e){

	var_dump($e);
	die('Format HTML incorrect. Html : ' . strlen($table) . ' car.');

}
if(!$dom){
	var_dump("Aucune table", $dom);
	echo $table;
	die('Trop gros ou format HTML incorrect. Html : ' . strlen($table) . ' car.');
}
$nRow = 0;


$td = array();
$tag = 'th';
$regex_trim = '/^[\x00\s]+|(\x00|\s)+$/';
$columns = false;
$rows = array();
foreach($dom->find('tr') as $element)
{
	switch($element->parent()->nodeName()){
		case 'tbody' :
			$row = array();
			$nCol = 0;
			foreach( $element->find('th, td') as $cell)  
			{
				if(is_array($columns))
					$row [$columns[$nCol++]] = preg_replace($regex_trim, '', $cell->plaintext);
				else
					$row [] = preg_replace($regex_trim, '', $cell->plaintext);
			}
			$nRow++;
			$rows[] = $row;
			break;

		case 'thead':
			if(!$columns){
				$columns = array();
				foreach( $element->find('th') as $cell)  
				{
					$columns [] = preg_replace($regex_trim, '', $cell->plaintext);
				}
				$next_row = true;
			}
			break;
		default:
			$next_row = true;
			break;
	}
	
}
return $rows;
?>