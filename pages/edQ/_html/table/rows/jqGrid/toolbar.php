<?php
/** 
 * Toolbar render with dimensions selectors
 * - Should be included in a form
 * - Defines $arguments['columns'] from $_REQUEST
 * - Returns $arguments with updated $arguments['columns']
 */

if(!isset($arguments) || !isset($arguments['rows']))
	return '$arguments["rows"] missing.';
$rows = $arguments['rows'];
$columns = $arguments['columns'];

if($rows && count($rows)){
	$srcColumns = array_keys($rows[0]);
	//add missing columns from existing columns
	foreach($srcColumns as $columnName){
		if(!isset($columns[$columnName]))
			$columns[$columnName]=array('label' => $columnName);
	}
}
else
	$srcColumns = $columns;

/* Groups */
$columnsGroups = array('x'=>array(), 'y'=>array());

//groups defined in request
if(isset($_REQUEST['q--columns-x'])){
	//reset
	foreach($columns as $columnName => $column){
		if(isset($column['group']))
			unset($columns[$columnName]['group']);
	}
	//set from request
	foreach($columnsGroups as $dim => $dimColumns){
		if(!isset($_REQUEST['q--columns-'.$dim]) || ! $_REQUEST['q--columns-'.$dim])
			continue;
		$columnsGroups[$dim] = array_combine( $_REQUEST['q--columns-'.$dim], $_REQUEST['q--columns-'.$dim]);
		
		foreach($columns as $columnName => $column){
			if(isset($columnsGroups[$dim][$columnName]))
				$columns[$columnName]['group'] = $dim;
		}
	}
	$arguments['columns'] = $columns;
}
else
	foreach($columnsGroups as $dim => $dimColumns)
		foreach($columns as $columnName => $column)
			if(isset($column['group']) && $column['group'] == $dim)
				$columnsGroups[$dim][$columnName] = true;


/* Agregate columns */
$columnsAgregs = array('count'=>array(), 'sum'=>array()
					   , 'avg'=>array(), 'std'=>array()
					   , 'rate'=>array()
					   , 'min'=>array(), 'max'=>array());
$translations = array('count'=>'Nombre', 'sum'=>'Somme', 'rate'=>'Taux'
					 , 'avg'=>'Moyenne', 'std'=>'Ecart-type'
					 , 'min'=>'Minimum', 'max'=>'Maximum'
					 , 'x' => 'Ligne', 'y' => 'Colonne'
					 );

//Agregate defined in request
if(isset($_REQUEST['q--columns-x'])){
	//reset
	foreach($columns as $columnName => $column)
		foreach($columnsAgregs as $agreg => $agregColumns)
			if(isset($column[$agreg]))
				unset($columns[$columnName][$agreg]);
	
	//set from request
	foreach($columnsAgregs as $agreg => $agregColumns)
		if(isset($_REQUEST['q--columns-'.$agreg])){
			if(count($_REQUEST['q--columns-'.$agreg]) == 1
			&& !$_REQUEST['q--columns-'.$agreg][0])
				$columnsAgregs[$agreg] = array();
			else
				$columnsAgregs[$agreg] = array_combine( $_REQUEST['q--columns-'.$agreg], $_REQUEST['q--columns-'.$agreg]);
			
			foreach($columns as $columnName => $column){
				if(isset($columnsAgregs[$agreg][$columnName]))
					$columns[$columnName][$agreg] = true;
			}
		}
}
else
	foreach($columnsAgregs as $agreg => $agregColumns)
		foreach($columns as $columnName => $column)
			if(isset($column[$agreg]) && $column[$agreg])
				$columnsAgregs[$agreg][$columnName] = true;

//Normalize
foreach($columnsAgregs as $agreg => $agregColumns)
	foreach($agregColumns as $columnName => $column){
		if(!isset($columns[$columnName]['formatter']))
			$columns[$columnName]['formatter'] = 'function(x){ return x===undefined || x===null ? "" : x;}';
		if(!isset($columns[$columnName]['sorttype']))
			$columns[$columnName]['sorttype'] = 'number';
	}
//update return value
$arguments['columns'] = $columns;
	
/* Colonnes X et Y */
?>
<ul style="list-style-type: none;">
<?php
	foreach($columnsGroups as $dim => $dimColumns){?>
		<li style="display: inline-block; vertical-align: top; ">
			<label>en <?=$translations[$dim]?> : </label>
			<br/>
			<select name="q--columns-<?=$dim?>[]" multiple>
			<?php foreach($srcColumns as $columnName => $column){
				if(is_numeric($columnName))
					if(is_array($column))
						$columnName = $column['name'];
					else
						$columnName = $column;
				?><option value="<?=$columnName?>" <?=isset($dimColumns[$columnName]) ? ' selected="selected"' : ''?>>
				<?=$columnName?></option><?php
			}
			?><option value="" <?=count($dimColumns) == 0 ? ' selected="selected"' : ''?>>(aucune)</option>
			</select>
		</li><?php
}?>
<?php
/* Colonnes Agregats */
?>
<?php
	foreach($columnsAgregs as $agreg => $agregColumns){?>
		<li style="display: inline-block;vertical-align: top; ">
			<label><?php
			?><input type="checkbox" <?= count($agregColumns) ?' checked="checked"' : ''?>
					 onclick="var $input = $(this.parentNode).nextAll('select');
				$input.toggle();
				if(!this.checked) $input.val('').children('option[selected]').removeAttr('selected');"/><?php
			?><?=$translations[$agreg]?></label>
			<?= ''/*var_dump($agregColumns)*/;?>
			<br/>
			<select name="q--columns-<?=$agreg?>[]" multiple <?=
				count($agregColumns)===0 ?' style="display: none;"' : ''?>>
			<option value="" <?=count($agregColumns) == 0 ? ' selected="selected"' : ''?>>(aucune)</option>
			<?php foreach($srcColumns as $columnName => $column){
				if(is_numeric($columnName))
					if(is_array($column))
						$columnName = $column['name'];
					else
						$columnName = $column;
				?><option value="<?=$columnName?>" <?=isset($agregColumns[$columnName]) ? ' selected="selected"' : ''?>>
				<?=$columnName?></option><?php
			}
			?></select>
		</li><?php
}?></ul>
<?php
return $arguments;
?>