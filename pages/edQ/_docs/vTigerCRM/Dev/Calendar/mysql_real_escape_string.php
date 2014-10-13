<pre><h3>include\database\PearDatabase.php</h3>

Symptôme : L'enregistrement des préférences utilisateur pour le calendrier
	pourrit le contenu du fichier <var>\user_privileges\user_privileges_1.php</var>
et plus rien n'est accessible.
 c'est Users->insertIntoEntityTable qui subit un plantage mais continue avec la mise à jour des privilèges.
 L'erreur est, en fait, dans PearDatabase->sql_escape_string().
 Cela n'apparait que sur un serveur Wamp particulier qui considère la function mysql_real_escape_string() comme obsolète.

Modifier PearDatabase.php, ligne ~1033
<code>

	//To get a function name with respect to the database type which escapes strings in given text
	function sql_escape_string($str)
	{
		if($this->isMySql())
			$result_data = <b>$this-&gt;mysql_escape_mimic</b>($str);//mysql_real_escape_string
		elseif($this-&gt;isPostgres())
			$result_data = pg_escape_string($str);
		return $result_data;
	}

	<b>/* ED141005
	 * mysql_real_escape_string is no more supported
	 * mysqli_real_escape_string need link object
	 * so, mimic
	*/
	function mysql_escape_mimic($inp) { 
		if(is_array($inp)) 
			return array_map(__METHOD__, $inp); 

		if(!empty($inp) && is_string($inp)) { 
			return str_replace(array('\\', "\0", "\n", "\r", "'", '"', "\x1a"), array('\\\\', '\\0', '\\n', '\\r', "\\'", '\\"', '\\Z'), $inp); 
		} 

		return $inp; 
	} </b>
</code>
</pre>