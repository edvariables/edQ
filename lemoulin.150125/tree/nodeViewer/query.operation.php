<?php /* Gestion de noeud de requête
UTF8 é

Les paramètres sont dans la table node_param.
"SQLSelect" => SQL
"SQLUpdate" => SQL
"SQLInsert" => SQL
"SQLDelete" => SQL
"Columns" => liste de colonnes ( interprétés via "array(" . $1 . ");" )
	"column" => "titre"
	"column" => {
		"text" => "nom"
		"text" => function($column, $rows, $viewer){ return $column["text"]; }
		"value" => function($row, $column, $viewer){ return $row; }
		"visible" => true
		"attributes" => array()
		"css" => function($column, $viewer){ return ''; }
	}
"Row" => modèle de ligne
	=> function($row, $index, $rows, $viewer){ return '<tr class="' . ($index % 2 === 0 ? 'even' : 'odd') . '">'; }
"Caption" => titre de la table ( interprétés via "return function($node, $rows, $viewer) { [return] " . $1 . "; };" )
"Foot" => pied de table ( interprétés via "return function($node, $rows, $viewer) { [return] " . $1 . "; };" )
*/

/***************
	action
****************/
require_once(dirname(__FILE__) . '/param.operation.php');

?>