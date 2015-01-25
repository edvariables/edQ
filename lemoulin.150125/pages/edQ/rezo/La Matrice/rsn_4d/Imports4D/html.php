<?php
if(!isset($node))
	$node = page::node(null);

$arguments_isset = isset($arguments);
$arguments = array_merge($_REQUEST, $arguments_isset ? $arguments : array());

if(!isset($view))
	$view = $arguments['view'];

$args = array(
	"domain" => node($node, null, 'path')
	, "param" => 'form'
	, "return" => 'value'
);

$defaults = array(
	'tables' => array(),
	'files-path' => '/Users/cogi4d/Desktop/Exports Tables 4D',
	'import-count' => INF,
	'import-start' => 0,
	'create-tables' => false,
	'truncate-tables' => false,
	'index-tables' => false,
	'charset' => 'Windows-1252',
);
$arguments = array_merge($_REQUEST, isset($arguments) ? $arguments : array());
if(!isset($arguments['q--submit'])){
	$args = page::call('/_System/Utilisateur/Preferences/get', $args);
	if(isset($args['value'])){
		$prefs = json_decode($args['value'], true);
		if(!is_object($prefs) && !is_array($prefs))
			$prefs = $defaults;
	}
	else
		$prefs = $defaults;
}
else {
	$prefs = array(
		'tables' => $arguments['q--tables'] ? $arguments['q--tables'] : $defaults['tables'],
		'files-path' => $arguments['q--files-path'] ? $arguments['q--files-path'] : $defaults['files-path'],
		'import-count' => $arguments['q--import-count'] && !$arguments['q--import-count-inf'] ? $arguments['q--import-count'] : $defaults['import-count'],
		'import-start' => $arguments['q--import-start'] ? $arguments['q--import-start'] : $defaults['import-start'],
		'charset' => $arguments['q--charset'] ? $arguments['q--charset'] : $defaults['charset'],
		'index-tables' => $arguments['q--index-tables'] ? $arguments['q--index-tables'] : $defaults['index-tables'],
		'create-tables' => $arguments['q--create-tables'] ? $arguments['q--create-tables'] : $defaults['create-tables'],
		'truncate-tables' => $arguments['q--truncate-tables'] ? $arguments['q--truncate-tables'] : $defaults['truncate-tables'],
	);
	$args['value'] = $prefs;
	page::call('/_System/Utilisateur/Preferences/set', $args);
}
//$prefs = $defaults;
//var_dump($prefs);


$tables = node('tables', $node, 'call', array(
	'files-path' => $prefs['files-path']
));
/* echo '<pre>'; var_dump($tables); echo '</pre>'; */

$charsets = array('UTF-8', 'Windows-1252', 'ISO-8859-15', 'x-mac-roman', 'macintosh');

?>
<h3>Importation des données dans rsn_4D</h3>
<?php
	//var_dump($arguments);
	$run = isset($arguments["q--run"]) && $arguments["q--run"];

	$selected_tables = isset($arguments["q--tables"]) ? $arguments["q--tables"] : $prefs['tables'];
	$selected_tables_names = array();
	if(is_array($selected_tables))
		foreach($selected_tables as $name)
			$selected_tables_names[$name] = 1;
		
/* formulaire d'options */
$uid = uniqid('form');
?>
<form id="<?=$uid?>" method="POST"
	  action="<?=page::url( $node )?>"
	  autocomplete="off" style="margin-bottom: 1em;">
	<fieldset><legend>Sélectionnez les tables à importer ainsi que les paramètres d'importation</legend>
	<table><tr>
		<td style="padding-right: 2em;">Tables à importer :
			<br/>
		<select name="q--tables[]" multiple size="<?=round(count($tables) * 2.2)?>"><?php
			foreach($tables as $name=>$table)
			if(isset($table['files-date'])){
			?><optgroup label="<?=$table['label']?>" title="<?=$table['file']?> - <?=$name?>">
			<?php for($nFile = 0; $nFile < $table['files']; $nFile++){
				$file = $name . ($nFile == 0 ? '' : '-' . ($nFile +1));
				?><option value="<?=$file?>" <?=isset($selected_tables_names[$name]) ? ' selected="selected"' : ''?>
						  ><?=$nFile == 0 ? $table['files-date'] : 'fichier ' . ($nFile + 1)?></option><?php
				}
			?></optgroup><?php
			}?>
		</select>
	</td>
	<td>
		<p>
		Répertoire des fichiers à importer : <input name="q--files-path" value="<?=$prefs['files-path']?>" size="40"/>
		</p>
		<p>Nombre de lignes à importer : <input name="q--import-count"
					value="<?=$prefs['import-count'] == INF ? '' : $prefs['import-count']?>" size="3"/>
			<label><input type="checkbox" name="q--import-count-inf"
						  <?=$prefs['import-count'] == INF ? ' checked="checked"' : ''?>/> Toutes</label>
		<br/>
		A partir de la ligne : <input name="q--import-start" value="<?=$prefs['import-start']?>" size="3"/>
		<br/>
		Jeu de caractères du fichier : <select name="q--charset"><?php
			foreach($charsets as $name){
			?><option value="<?=$name?>" <?=$prefs['charset'] == $name ? ' selected="selected"' : ''?>
					  ><?=$name?></option><?php
			}?>
		</select>
		</p>
		<p><label><input type="checkbox" name="q--create-tables" <?=$prefs['create-tables'] ? ' checked="checked"' : ''?>/> (Re)créer les tables</label>
		<br/>
		<label><input type="checkbox" name="q--truncate-tables" <?=$prefs['truncate-tables'] ? ' checked="checked"' : ''?>/> Purger les tables</label>
		<br/>
		<label><input type="checkbox" name="q--index-tables" <?=$prefs['index-tables'] ? ' checked="checked"' : ''?>
					  /> Indexer les tables (aucune importation n'est effectuée)</label>
		</p>
		<p><label><input type="checkbox" name="q--run" <?=$run ? ' checked="checked"' : ''?>/> Exécuter les importations
			&nbsp;<i>(cocher sinon seul un pré-test est effectué)</i></label>
		</p>
		<br/>
		<input type="hidden" name="q--submit" value="1"/>
		<input type="submit" value="Exécuter" style="margin-left: 4em;"/>
	</td></tr>
	</table>
	</fieldset>
</form>
<?= isset($view) ? $view->searchScript($uid) : '$view no set'?>

<?php 
	if(isset($arguments['q--submit'])){
		ini_set('max_execution_time', 3600);
		$columns = array();
		$node_name_prev = '';
		foreach($selected_tables_names as $file=>$selected){
			$nFile = preg_replace('/^.*\-(\d+)$/', '$1', $file);
			if(is_numeric($nFile))
				$node_name = preg_replace('/\-\d+$/', '', $file);
			else {
				$nFile=false;
				$node_name = $file;
			}
			echo '<br>$file = '; var_dump($file);
			echo '<br>$node_name = '; var_dump($node_name);
			if($node_name_prev != $node_name){
				$table_node = node('tables/' . $node_name, $node);
				if(!$table_node){
					throw new Exception('Le noeud tables/' . $node_name . ' n\'existe pas.');
				}
				$columns = node($table_node, null, 'call', array(
					'return' => 'columns'
				));
					
				$node_name_prev = $node_name;
			}
			if($prefs['index-tables']){
				if(!$nFile){
					node('indexes', $node, 'call', array(
						'simulate' => !$run,
						'table' => $node_name,
						'columns' => $columns,
					));
				}
			}
			else {
				$file = $tables[$node_name]['file'] . ($nFile ? '-' . ($nFile - 1) : '');//reconstruit le nom du fichier
				echo '<br>$file = '; var_dump($file);
				echo '<br>';
				node('csv', $node, 'call', array(
					'simulate' => !$run,
					'file' => utf8_decode( helpers::combine($prefs['files-path'], $file . '.csv') ),
					'table' => $node_name,
					'create_table' => !$nFile && $prefs['create-tables'],
					'truncate_table' => !$nFile && $prefs['truncate-tables'],
					'charset' => $prefs['charset'],
					'max_rows' => $prefs['import-count'],
					'skip_rows' => $prefs['import-start'],
					'columns' => $columns,
				));
			}
		}
	}
?>
	