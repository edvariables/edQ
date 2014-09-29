<?php /* Gestion du contenu d'un fichier
UTF8 é
*/

require_once(dirname(__FILE__) . '/../helpers.php');
require_once('query.php');
class nodeViewer_query_call extends nodeViewer_query {
	public $domain = 'query';
	public $name = 'query.call';
	public $text = 'Résultat';
	
	public $rows;
	public $columns;
	public $rowIndex;
	public $columnIndex;
	
	public function html($node, $options = false){
		global $tree;
		if(!isset($node["path"])){
			$node = $tree->get_node((int)$node['id'], array('with_path' => true, 'full' => false));
		}
		// instance de node
		$node = Node::fromClass($this->domain, $node);
		
		$href = $_SERVER["REQUEST_URI"];
		$head = '<div class="edq-toolbar">'
			. '<a class="edq-refresh" href="' . $href . '"'
			. ' onclick="var $parent = $(this).parents(\'.ui-widget-content:first\'); '
				. ' $.ajax(this.getAttribute(\'href\')).done(function(html){ $parent.html(html); }).fail(function(o,err){ alert(err) });'
				. ' return false;'
			. '">rafraîchir</a>'
			. '</div>';
		
		$sql = $node->param_value("SQLSelect");
		
		$exists = true;
		
		$file = $node->findFile('dataSource.php');
		if($file == null)
			$content = '<div class="edq-error">Le fichier dataSource.php n\'existe pas dans ' . $node->pagePath . '.</div>';
		
		else if($sql === null || $sql === '')
			$content = '<div class="edq-error">La requête SQLSelect n\'existe pas.</div>';
		
		else if($exists){
		
			ob_start();
			include(utf8_decode($file));
			
			global $db;
			/* SQLSelect */
			$this->rows = $rows = $db->all( $sql );
			//var_dump($this->rows);
			/* Columns */
			$this->columns = $columns = $node->param_value("Columns");
			if($columns != ''){
				$columns = eval('return array(' . $columns . ');');
				if(!is_associative($columns)){
					$src = $columns;
					$columns = array();
					foreach($src as $name)
						$columns[$name] = $name;
				}
			}
			else {
				$columns = array();
				if(isset($rows[0]) && is_array($rows[0]))
					foreach(array_keys($rows[0]) as $name)
						$columns[$name] = $name;
			}
			$tdCount = 0;
			foreach($columns as $name => $column)
				if(!is_array($column)) break;
				else if(!isset($column["visible"]) || $column["visible"]){
					$column["index"] = $tdCount++;
				}
			/* Caption */
			$caption = $node->param_value("Caption", '<null>');
			if($caption == '<null>')
				$caption = $node->name;
			else if($caption != ''){
				$eval = $caption;
				if(!is_callable($eval)){
					if(!preg_match('/^(\s*\/\*.*\*\/)*\s*return\s+function\s*\(/', $eval))
						if(!preg_match('/^(\s*\/\*.*\*\/)*\s*function\s*\(/', $eval)){
							if(!preg_match('/\breturn\b/', $eval))
								$eval = 'return function($node, $rows, $viewer){ return ' . $eval . ';}';
							else
								$eval = 'return function($node, $rows, $viewer){' . $eval . ';}';
						}
						else
							$eval = 'return ' . $eval;
					//var_dump($eval);
					try {
						ob_start();
						$caption = eval($eval . ';');
						if ('' !== $error = ob_get_clean()) {
							throw new Exception($error);
						}
					}
					catch(Exception $ex){
						$caption = 'ERREUR dans la préparation de Caption' . $ex->getMessage()
							. '<pre><h3>Code source</h3>' . $eval . '</pre>';
					}
				}
				if(is_callable($caption)){
					try {
						ob_start();
						$caption = $caption($node, $rows, $this);
						if ('' !== $error = ob_get_clean()) {
							throw new Exception($error);
						}
					}
					catch(Exception $ex){
						$caption = 'ERREUR dans Caption($node, $rows, $viewer)' . $ex->getMessage()
							. '<pre><h3>Code source</h3>' . $eval . '</pre>';
					}
				}
			}	
			
			/* Foot */
			$foot = $node->param_value("Foot", '<null>');
			if($foot == '<null>')
				$foot =  count($rows) . ' ligne' . (count($rows) > 1 ? 's' : '');
			else if($foot != ''){
				$eval = $foot;
				if(!is_callable($eval)){
					if(!preg_match('/^(\s*\/\*.*\*\/)*\s*return\s+function\s*\(/', $eval))
						if(!preg_match('/^(\s*\/\*.*\*\/)*\s*function\s*\(/', $eval)){
							if(!preg_match('/\breturn\b/', $eval))
								$eval = 'return function($node, $rows, $viewer){ return ' . $eval . '}';
							else
								$eval = 'return function($node, $rows, $viewer){' . $eval . '}';
						}
						else
							$eval = 'return ' . $eval;
					//var_dump($foot);
					try {
						ob_start();
						$foot = eval($eval . ';');
						if ('' !== $error = ob_get_clean()) {
							throw new Exception($error);
						}
					}
					catch(Exception $ex){
						$foot = 'ERREUR dans la préparation de Foot' . $ex->getMessage()
							. '<pre><h3>Code source</h3>' . $eval . '</pre>';
					}
				}
				if(is_callable($foot)){
					try {
						ob_start();
						$foot = $foot($node, $rows, $this);
						if ('' !== $error = ob_get_clean()) {
							throw new Exception($error);
						}
					}
					catch(Exception $ex){
						$foot = 'ERREUR dans Foot($node, $rows, $viewer)' . $ex->getMessage()
							. '<pre><h3>Code source</h3>' . $eval . '</pre>';
					}
				}
			}
			$uid = uniqid('form-');
?><table id="<?=$uid?>" class="edq" style="overflow: scroll;">
	<caption><?=$caption?></caption>
	<?php /* ?><thead><tr><?php
		$this->columnIndex = 0;
		foreach($columns as $name => $column){
			if(!is_array($column) || !isset($column["visible"]) || $column["visible"]){
				$text = is_array($column)
					? isset($column["text"])
						? is_callable($column["text"])
							? $column["text"]($column, $this)
							: $column["text"]
						: $name
					: $column;
				$this->columnIndex++;
				$class = is_array($column) && isset($column["pk"]) && $column["pk"] ? ' class="pk"' : '';
				?><th name="<?=$name?>"<?=$class?>><?=$text?></th><?
			}
		}
	?></tr>
	</thead><?php */ ?>
	<tbody><?php
	$this->rowIndex = 0;
	foreach($rows as $row){
		?><tr><?php
			$this->columnIndex++;
			foreach($columns as $name => $column){
				if(!is_array($column) || !isset($column["visible"]) || $column["visible"]){
					$value = isset($row[$name])
						? is_array($column)
							? isset($column["value"])
								? is_callable($column["value"])
									? $column["value"]($row, $column, $this)
									: $column["value"]
								: htmlentities( $row[$name] )
							: htmlentities( $row[$name] )
						: ''
					;
					$attrs='';
					if(isset($row[$name]) && is_array($column) && isset($column["attributes"])){
						foreach($column["attributes"] as $attr => $vattr){
							$attrs .= ' ' . $attr . '="' . $vattr . '"';
						}
					}
					$style = isset($row[$name])
						? is_array($column)
							? isset($column["css"])
								? is_callable($column["css"])
									? $column["css"]($row, $column, $this)
									: $column["css"]
								: ''
							: ''
						: ''
					;
					if($style !== '')
						$attrs .= ' style="' . $style . '"';
					?><td<?= $attrs ?>><?=$value?></td><?php
				}
			}
		?></tr><?php
		$this->rowIndex++;
	}
	?></tbody>
	<tfoot><tr><td colspan="<?=$tdCount?>"><?=$foot?></tr>
	</tfoot>
</table>
<style>
#<?=$uid?> {
	border-spacing: 0px;
	 border-collapse: collapse; 
	 border: 1px outset #333333;
}
#<?=$uid?> tbody {
	 background-color: white;
}
#<?=$uid?> tbody > tr > td {
	padding: 1px 4px 1px 6px;
	 border: 1px solid #333333;
}
#<?=$uid?> tbody > tr > td:nth-of-type(1) {
	text-align: right;
}
#<?=$uid?> thead > tr > th {
	text-align: center;
	 border: 1px solid #333333;
}
</style><?php
			$content = ob_get_clean();
			//var_dump($content);
		}
		else {
			$content = '<i>fichier absent</i>';
		}
			
		return array(
			"title" => $node->name
			, "content" => $head . $content
		);
	}
}

?>
