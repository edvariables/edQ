<?php
global $tree;
?>
<h2><?=$node['nm']?></h2>
Classe <var>tree</var>
<ul class="edq-doc">
	<li><h3>tree::get_node_by_name()</h3>
		<ul>
			<li>retourne un noeud selon son nom</li>
			<li>Exemple : <code>$node = tree::get_node_by_name($name, $refersTo);</code></li>
		</ul></li>
	<li><h3>tree::get_child_by_name()</h3>
		<ul>
			<li>retourne un noeud enfant selon son nom</li>
			<li>Exemple : <code>$node = tree::get_child_by_name($child_name, $refersTo);</code></li>
		</ul></li>
	<li><h3>tree::get_id_by_name()</h3>
		<ul>
			<li>retourne l'id d'un noeud selon son nom</li>
			<li>Exemple : <code>$id = tree::get_id_by_name($name, $refersTo);</code></li>
		</ul></li>
	<li><h3>tree::get_id_by_name()</h3>
		<ul>
			<li>retourne l'id d'un noeud selon son nom</li>
			<li>Exemple : <code>$id = tree::get_id_by_name($name, $refersTo);</code></li>
		</ul></li>
	
	<li><h3>global $tree</h3>
		<ul>
			<li>objet $tree global</li>
		</ul></li>
	
	<li><h3>$tree->get_node()</h3>
		<ul>
			<li>recherche le noeud dans la table tree_data</li>
			<li>Exemple : <code>$node = $tree->get_node($id);</code></li>
		</ul></li>
	
	<li><h3>$tree->get_parent()</h3>
		<ul>
			<li>noeud parent</li>
			<li>Exemple : <code>$tree->get_parent(<?= $node['id'] ?>)['nm'] //returns <?=var_dump($tree->get_parent($node['id'])['nm'])?></code></li>
		</ul></li>
	
	<li><h3>$tree->get_children()</h3>
		<ul>
			<li>noeuds enfants</li>
			<li>Exemple : <code>$tree->get_children(<?= $node['id'] ?>) //returns <?=var_dump($tree->get_children($node['id']))?></code></li>
		</ul></li>
	
	<li><h3>$tree->get_path_string()</h3>
		<ul>
			<li>chemin</li>
			<li>Exemple : <code>$tree->get_path_string(<?= $node['id'] ?>) //returns <?=var_dump($tree->get_path_string($node['id']))?></code></li>
		</ul></li>
	
	<li><h3>$tree->get_path()</h3>
		<ul>
			<li>noeuds parents sous forme de tableau</li>
			<li>Exemple : <code>$tree->get_path(<?= $node['id'] ?>) //returns <?=var_dump($tree->get_path($node['id']))?></code></li>
		</ul></li>
	
	<li><h3>$tree->dump()</h3>
		<ul>
			<li>dump</li>
			<li>Exemple : <code>$tree->dump() //returns <?=var_dump($tree->dump())?></code></li>
		</ul></li>
</ul>