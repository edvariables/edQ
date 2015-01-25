<h2><?=$node['nm']?></h2>
Classe statique <var>page</var>
<ul class="edq-doc">
	<li><h3>page::get_root_path()</h3>
		<ul><li><?=page::get_root_path()?></li>
		</ul></li>
	<li><h3>page::file()</h3>
		<ul><li><?=page::file($node)?></li>
		</ul></li>
	<li><h3>page::file_url()</h3>
		<ul><li><?=page::file_url($node)?></li>
		</ul></li>
	<li><h3>page::folder()</h3>
		<ul><li><?=page::folder($node)?></li>
		</ul></li>
	<li><h3>page::folder_url()</h3>
		<ul><li><?=page::folder_url($node)?></li>
		</ul></li>
	<li><h3>page::execute()</h3>
		<ul><li><code>page::execute($search, [$refers_to = null], [$extension = ".php"], [&$arguments = null])</code></li>
		<li><a href="#">voir page::call()</a></li>
		</ul></li>
	<li><h3>page::call()</h3>
		<ul><li><code>page::call($search, [$arguments = null], [$refers_to = null], [$extension = ".php"])</code></li>
		<li>dans la page appelée, <var>$node</var> n'est pas défini<code>
	$node = node($node); //garantit la disponibilité de $node</code></li>
		<li><var>$search</var><pre>Recherche en référence à $refers_to
	<code>'..dataSource'</code> : chez les parents
	<code>'.dataSource'</code> : au niveau de la référence ou chez les parents
	<code>'dataSource'</code> : au niveau de la référence
	<code>':dataSource'</code> : dans la descendance
	<code>'/_System/dataSource'</code> : à partir de la racine
	peut être un identifiant numérique : <code>page::execute( $node['pid'] ); //parent id</code>
	<code>page::execute( 1025 ); // non transposable d'un serveur à l'autre</code>
	peut être un noeud : <code>page::execute( $child_node ); </code>
		</pre></li>
		<li><var>$arguments</var><pre>Arguments transmis à la page appelée
<code>$args = array('return' => 'value');
page::call(':sub_page', $args, $node);
page::execute(':sub_page', $node, '.php', $args);</code>
	</pre></li>
	<li><var>$refers_to</var><pre>Référence de recherche.
	peut être passée par __FILE__ : <code>page::execute(':sub_page', __FILE__);</code>
	peut être un noeud : <code>page::execute(':sub_page', $node);</code>
	peut être l'identifiant numérique d'un noeud : <code>page::execute(':sub_page', $node['id'] );</code>
	peut être NULL si $search est un identifiant, un noeud ou un chemin commençant par /.
	 	<code>page::call('/_System/Pages');</code>
	sinon, si NULL, une recherche dans la trace Php (par debug_backtrace()) retrouve le fichier .php appelant.
			laisser le système utilisé cette méthode est déconseillé car plus gourmand en ressource,
			alors qu'il suffit d'ajouter l'argument <code>, __FILE__</code>. : 
			<code>page::call(':sub_page'); //déconseillé</code>
			<code>page::execute(':sub_page', __FILE__); //conseillé</code>
			<code>page::execute(':sub_page', $node); //mieux mais en s'assurant de l'existence de $node</code></pre>
	<li><var>valeur de retour</var><pre>L'instruction <code>return</code> dans le code de la page permet de retourner une valeur.</li>
		</ul></li>
	<li><h3>page::node($search, [$refers_to = null])</h3>
		<ul><li><?=var_dump(page::node($node))?></li>
		</ul></li>
	<li><h3>page::id()</h3>
		<ul><li><?=page::id($node)?></li>
		</ul></li>
	<li><h3>page::url()</h3>
		<ul><li><?=page::url($node)?></li>
		</ul></li>
	<li><h3>page::form_submit_script($form_uid, $options = null)</h3>
		<ul>Retourne le script qui permet la validation du formulaire
			<li>
				<pre><code>&lt;?php $form_uid = uniqid('form'); ?&gt;
&lt;form id="$lt;?=$form_uid$gt;"&gt;
&lt;input type="submit" value="Valider"/&gt;
&lt;/form&gt;
&lt;?=page::form_submit_script($form_uid)?&gt;
</code>retourne :<code>
<?= htmlentities(page::form_submit_script(uniqid('form')))?></code></pre>
			</li>
		</ul></li>
	<li><h3>page::view()</h3></li>
	<li><h3>page::html()</h3></li>
	<li><h3>TREE_ROOT</h3>
		<ul><li><?=TREE_ROOT?></li>
		</ul></li>
	<li><h3>TREE_ROOT_NAME</h3>
		<ul><li><?=TREE_ROOT_NAME?></li>
		</ul></li>
</ul>