<?php /* Conversion de html en fichier .csv
	Les données peuvent être fournies sous forme html.
	Le fournisseur est désigné par la propriété $arguments['node'].
	Ce noeud est appelé en transmettant la variable $arguments.
	Ce noeud fournit les données en retour d'après l'existence de l'argument 'node--get' :
		$arguments['node--get'] = 'rows';
		//il faut, bien sûr, que le noeud qui fournit les données traite lui-même $arguments['node--get'];
		$rows = page::call($arguments['node'], $arguments, __FILE__);
				
	$arguments['csv--separ--column'] : séparateur de colonnes. Par défaut ';'
	$arguments['csv--separ--row'] : séparateur de lignes. Par défaut '\n'
	$arguments['csv--separ--field'] : caractères englobant les textes des champs. Par défaut chr(0), le standard étant '"'.
	$arguments['csv--numeric--precision'] : nombre de chiffres après la décimale. Par défaut, 3;
	
	Sinon, le noeud retourne du html et les données sont extraites de <table/>. Le code html doit être rigoureux (fermetures de balises).
*/


//echo(' csv entree $arguments : '); var_dump($arguments);
	if(!isset($arguments))
		$arguments = array();

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

	if(!isset($arguments['node--get']))
		if(isset($_REQUEST['node--get']))
			$arguments['node--get'] = $_REQUEST['node--get'];
		else
			$arguments['node--get'] = 'rows';
	unset($arguments[$arguments['node--get']]);

	/* execution */
	ob_start();

	$rows = page::call($nodesource, $arguments, __FILE__);
	
	$table = ob_get_clean(); 
	
	if(!is_array( $rows )){
		// conversion html -> rows
		$arguments['html'] = $table;
		$rows = page::call('/_format/rows/from html', $arguments);
	}
	// conversion rows -> csv
	$arguments['rows'] = $rows;
	return node('from rows', node($node, __FILE__)
				, 'call', $arguments);
?>