<h2><?=$node['nm']?></h2>
<ul class="edq-doc">
function globale d'accès à un noeud de <var>$tree</var>
	<li><ul><h3>node([$search], [$refers_to], [[$options], ][$method], [$method_options])</h3>
		<li><var>$search</var><pre>Recherche en référence à $refers_to
	<code>'..dataSource'</code> : chez les parents
	<code>'.dataSource'</code> : au niveau de la référence ou chez les parents
	<code>'dataSource'</code> : au niveau de la référence
	<code>':dataSource'</code> : dans la descendance
	<code>'/_System/dataSource'</code> : à partir de la racine
	peut être un identifiant numérique : <code>node( $node['pid'] ); //parent id</code>
	<code>node( 1025 ); // non transposable d'un serveur à l'autre</code>
	peut être un noeud : <code>node( $child_node ); </code>
		</pre></li>
	<li><var>$refers_to</var><pre>Référence de recherche.
	peut être passée par __FILE__ : <code>node(':sub_page', __FILE__);</code>
	peut être un noeud : <code>node(':sub_page', $node);</code>
	peut être l'identifiant numérique d'un noeud : <code>node(':sub_page', $node['id'] );</code>
	peut être NULL si $search est un identifiant, un noeud ou un chemin commençant par /.
	 	<code>node('/_System/Pages'); //<?=node('/_System/Pages')['nm']?></code>
	sinon, si NULL, une recherche dans la trace Php (par debug_backtrace()) retrouve le fichier .php appelant.
			laisser le système utilisé cette méthode est déconseillé car plus gourmand en ressource,
			alors qu'il suffit d'ajouter l'argument <code>, __FILE__</code>. : 
			<code>node(':sub_page'); //déconseillé</code>
			<code>node(':sub_page', __FILE__); //conseillé</code>
			<code>node(':sub_page', $node); //mieux mais en s'assurant de l'existence de $node</code></pre>
	<li><var>$options</var><pre>Option d'initialisation du noeud par tree</pre>
	<li><var>$method</var><pre>Méthode appelée en fin de fonction</pre>
	<li><var>$method_options</var><pre>Argument passé à $method</pre>
	<li><var>valeur de retour</var><pre>retourne le noeud fournit par tree : <?php var_dump(node());?>
Si $method est fourni, retourne <code>$method($this, $options)</code></pre>
	
	<li><h4>Exemples</h4>
	<li><pre><code>//retourne le noeud ayant appelé la fonction
node() //Déconseillé parce qu'utilise plus de ressources machine.
node(__FILE__) //Dommage si on connait déjà la variable globale $node
node($node) //Préférable si on évalue que $node est le plus souvent défini.
node($node, __FILE__) //Idéal, mais ethétiquement lourd.
</code></pre>
		
	<li><code>echo node('/_System/Pages')['nm'];</code><pre><?=node('/_System/Pages')['nm']?></pre>
	</ul></li>
	
	<li><ul><h3>méthode 'call'</h3>
		<li><pre>Appel
	<code>node('..my_func', $node, 'call', $arguments)</code>
	équivaut à
	<code>page::call('..my_func', $arguments, $node)</code>
	
	<code>node('/_System/debug/callstack', $node, 'call');</code>

	<fieldset><legend><code>&lt;?php var_dump( node('/_System/debug/callstack', $node, 'call') ) ?&gt;</code></legend>
	<?php var_dump( node('/_System/debug/callstack', $node, 'call') ) ?></fieldset>
	
	<fieldset><legend><code>&lt;?php var_dump( node('/_System/debug/arguments', $node, 'call', $arguments) ) ?&gt;</code></legend>
	<?php var_dump( node('/_System/debug/arguments', $node, 'call', $arguments) ) ?></fieldset>
	
	</pre>
	</ul></li>
	<li><ul><h3>autres méthodes</h3>
		<li><pre><var>url</var>
	<code>node('/_html/bloc', $node, 'url')</code>
	retourne
	<code><?=node('/_html/bloc', $node, 'url');?></code>
	</pre>
		<li><pre><var>html</var>
	<code>node('/_html/image/edQ', $node, 'html')</code>
	retourne
	<code><?=node('/_html/image/edQ', $node, 'html');?></code>
	</pre>
		<li><pre><var>view</var>
	<code>node('..my_func', $node, 'view')</code>
	retourne
	<code><?=node('/_html/image/edQ', $node, 'view');?></code>
	</pre>
		<li><pre><var>viewer</var>
	<code>node('..my_func', $node, 'viewer')</code>
	retourne
	<code><?=var_dump(node('/_html/bloc', $node, 'viewer'));?></code>
	</pre>
	</ul></li>
		<li><pre><var>file</var>
	<code>node('..my_func', $node, 'file')</code>
	retourne
	<code><?=node('/_html/bloc', $node, 'file');?></code>
	</pre>
		<li><pre><var>file_url</var>
	<code>node('..my_func', $node, 'file_url')</code>
	retourne
	<code><?=node('/_html/image/edQ', $node, 'file_url');?></code>
	</pre>
		<li><pre><var>folder</var>
	<code>node('..my_func', $node, 'folder')</code>
	retourne
	<code><?=node('/_System/debug/callstack', $node, 'folder');?></code>
	</pre>
		<li><pre><var>name</var>
	<code>node('..my_func', $node, 'name')</code>
	retourne
	<code><?=node('/_System/debug/callstack', $node, 'name');?></code>
	</pre>
</ul>