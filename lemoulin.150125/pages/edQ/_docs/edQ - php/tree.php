<?php
global $tree;
$node = node($node, __FILE__);
?>
<h2><?=$node['nm']?></h2>
Classe <var>tree</var>
<ul class="edq-doc">
	<li><h3><code>include('tree/class.tree.php');</code></h3>
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
	
	<li><h3>$tree->get_node($id, $options = array(), $errorIfNotExists = true)</h3>
		<ul>
			<li>recherche le noeud dans la table tree_data</li>
			<li><var>$id</var><pre>
Identifiant numérique du noeud dans la base</pre></li>
			<li><var>$options</var><pre><code>array(
	'with_path' =&gt; FALSE, // generates $node['path']
	'with_children' =&gt; FALSE, // generates $node['children']
	'deep_children' =&gt; FALSE,
	'full' =&gt; FALSE, // add all fields
	'design' =&gt; FALSE, // allows design only node
)</code></pre></li>
			<li><var>$errorIfNotExists</var><pre><code>TRUE</code>
Provoque une exception si <var>$id</var> ne permet pas de trouver le noeud.
Signifie qu'il n'existe effectivement pas dans la base ou bien qu'il est en design-only alors que <var>$options['design']</var> est FALSE.
</pre></li>
			<li>Exemple : <code>$node = $tree->get_node($id);</code></li>
		</ul></li>
	
	<li><h3>$tree->get_parent()</h3>
		<ul>
			<li>noeud parent</li>
			<li>Exemple : <code>$tree->get_parent(<?= $node['id'] ?>)['nm'] //returns <?php $n=$tree->get_parent($node['id']); var_dump($n['nm'])?></code></li>
		</ul></li>
	
	<li><h3>$tree->get_children()</h3>
		<ul>
			<li>noeuds enfants</li>
			<li>Exemple : <code>$tree->get_children(<?= $node['id'] ?>) //returns <?php $n=$tree->get_parent($node['id']); var_dump($n['id'])?></code></li>
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
			<li>Exemple : <code>$tree->dump() /* returns : */<?php $tree->dump()?>
/*fin du dump*/</code></li>
		</ul></li>
</ul>