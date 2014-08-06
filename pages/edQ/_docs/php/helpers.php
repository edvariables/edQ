<h2><?=$node['nm']?></h2>
Classe statique <var>helpers</var>
<ul class="edq-doc">
	<li><h3>get_db()</h3>
		<ul>
			<li>retourne une connexion à la base de données</li>
			<li>cherche un dataSource dans le dossier ou dans les ascendants</li>
			<li>Exemple : <code>$db = get_db();
	$rows = $db->all($sql, $params);</code></li>
		</ul></li>
	<li><h3>helpers::combine()</h3>
		<ul>
			<li><?=helpers::combine('RootPath/SubDir', 'MyFile.php')?></li>
		</ul></li>
	<li><h3>ifNull()</h3>
		<ul>
			<li>retourne une valeur par défaut si l'argument est null</li>
			<li>Exemple : <code>echo ifNull(null, '(null)');</code></li>
		</ul></li>
	<li><h3>is_associative()</h3>
		<ul>
			<li>détermine si un tableau est de type associatif</li>
			<li>Exemple : <code>var_dump(is_associative(array(4,8))); <?php
				var_dump(is_associative(array(4,8)));?></code></li>
			<li>Exemple : <code>var_dump(is_associative(array('first'=> 4, 'second' => 8)));<?php
				 var_dump(is_associative(array('first'=> 4, 'second' => 8)));?></code></li>
		</ul></li>
	<li><h3>is_design()</h3>
		<ul>
			<li>détermine si l'utilisateur est en mode design.</li>
			<li>se base sur <var>$_REQUEST['design']</var>.</li>
		</ul></li>
	<li><h3>user_right()</h3>
		<ul>
			<li>détermine un droit de l'utilisateur.</li>
			<li>Exemple : <code>var_dump(user_right('facture', 15));</code></li>
			<li>se base sur <var>$_SESSION['edq-user']['rights']</var>.</li>
		</ul></li>
	<li><h3>rrmdir()</h3>
		<ul>
			<li>supprime les fichiers et les dossiers non-vides de façon récursive.</li>
		</ul></li>
	<li><h3>rcopy()</h3>
		<ul>
			<li>copie les fichiers et les dossiers non-vides de façon récursive.</li>
		</ul></li>
	<li><h3>get_local_temp_dir()</h3>
		<ul>
			<li>répertoire temporaire local à edQ</li>
			<li>Exemple : <code>var_dump(get_local_temp_dir()); <?php
				var_dump(get_local_temp_dir());?></code></li>
		</ul></li>
	<li><h3>get_temp_dir()</h3>
		<ul>
			<li>répertoire temporaire de Php (fourni par la fonction standard sys_get_temp_dir())</li>
			<li>si Apache n'a pas les droits d'écriture, utilise le répertoire temporaire local</li>
			<li>Exemple : <code>var_dump(get_temp_dir()); <?php
				var_dump(get_temp_dir());?></code></li>
		</ul></li>
	<li><h3>helpers::get_pages_path()</h3>
		<ul>
			<li>retourne le chemin des pages</li>
			<li>équivalent à page::get_root_path()</li>
			<li><code>helpers::get_pages_path();
//returns <?=helpers::get_pages_path()?></code></li>
			<li><code>page::get_root_path();
//returns <?=page::get_root_path()?></code></li>
		</ul></li>
	<li><h3>helpers::nodeFile_mv()</h3>
		<ul>
			<li>déplace un fichier + répertoire d'un noeud</li>
		</ul></li>
	<li><h3>helpers::nodeFile_cp()</h3>
		<ul>
			<li>copie un fichier + répertoire d'un noeud</li>
		</ul></li>
	<li><h3>helpers::nodeFile_rm()</h3>
		<ul>
			<li>supprime un fichier + répertoire d'un noeud</li>
		</ul></li>
	<li><h3>helpers::aasort()</h3>
		<ul>
			<li>tri un tableau associatif de tableaux associatifs sur la propriété fournie</li>
			<li><code>$arr = array( array("id" => 1, "name" => "Z"), array("id" => 2, "name" => "A"));
helpers::aasort($arr);
var_dump($arr);
//returns <?php $arr = array( array("id" => 1, "name" => "Z"), array("id" => 2, "name" => "A"));
				helpers::aasort($arr, 'name');
				var_dump($arr);
				echo ("is_associative : "); print_r(is_associative($arr));
				?></code></li>
		</ul></li>
</ul>