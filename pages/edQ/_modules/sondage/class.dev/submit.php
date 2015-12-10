<?php
if(!isset($_POST)
|| !isset($_POST['submit'])
|| !$_POST['submit'])
	return false;

return function($dataNodeId, $user, $isAdmin, $useCookies, $tables, &$recordsChanged, &$ownedQuestions, &$ownedParticipants){
	$node = node($node, __FILE__);
	if(!is_array($tables)){
		?>/*Paramètre $tables manquant.*/<?php
		return false;
	}
	if(!$dataNodeId){
		?>/*Paramètre dataNodeId manquant.*/<?php
		return false;
	}
	//données supprimées et données modifiées
	//normalisation
	$recordsDeleted = array();
	$recordsArchived = array();
	foreach($tables as $tableName => $table){
		if( preg_match_all('/^(\w+)_archived$/', $tableName, $matches) ){
			if(is_array($table))
				$recordsArchived[$matches[1][0]] = $table;
			$tables[$tableName] = false;
		}
		else {
			if(!array_key_exists($tableName, $recordsChanged))
				$recordsChanged[$tableName] = array();
			if(!array_key_exists($tableName, $recordsArchived))
				$recordsArchived[$tableName] = array();
			$recordsDeleted[$tableName] = array();
		}
	}

	//reset des data (purge des orphelins)
	if($_POST['full-data'] && $isAdmin)
		$tables = clone $recordsChanged;

function utf8_to_htmlentities ($string) {
    /* Only do the slow convert if there are 8-bit characters */
    /* avoid using 0xA0 (\240) in ereg ranges. RH73 does not like that */
    if (!preg_match("/[\200-\237]/", $string)
     && !preg_match("/[\241-\377]/", $string)
    ) {
        return $string;
    }

    // decode three byte unicode characters
    $string = preg_replace("/([\340-\357])([\200-\277])([\200-\277])/e",
        "'&#'.((ord('\\1')-224)*4096 + (ord('\\2')-128)*64 + (ord('\\3')-128)).';'",
        $string
    );

    // decode two byte unicode characters
    $string = preg_replace("/([\300-\337])([\200-\277])/e",
        "'&#'.((ord('\\1')-192)*64+(ord('\\2')-128)).';'",
        $string
    );

    return $string;
}
	//pour chaque paramètre envoyé
	foreach($_POST as $param => $value){
		if( preg_match_all('/^(reponse|participant|question)(_deleted|_archived)?_([^_]+|q(\w+)_p(\w+))$/', $param, $matches) ){
			$value = utf8_to_htmlentities($value);
			//var_dump($matches);
			$table = $matches[1][0];
			$deleted = $matches[2][0] == '_deleted';
			$archived = $matches[2][0] == '_archived';
			$nKey = $matches[3][0];
			if($deleted
			|| ($value === '' && !isset($tables[$table.'s'][$nKey]))
			  ){ /* NE PAS FAIRE : || (!$isAdmin && $value === '')){*/
				$recordsDeleted[$table . '_deleted_'.$nKey] = true;
				if(isset($tables[$table.'s'][$nKey]))
					unset($tables[$table.'s'][$nKey]);
			}
			elseif($archived){ 
				$recordsArchived[$table.'s'][$nKey] = date('d/m/Y');
				if(isset($tables[$table.'s'][$nKey]))
					unset($tables[$table.'s'][$nKey]);
			}
			else {

				if($table == 'reponse') {
					$nQuestion = $matches[4][0];
					$nParticipant = $matches[5][0];
					if(!isset($tables['questions'][$nQuestion])
					   || !isset($tables['participants'][$nParticipant])
					   || isset($recordsDeleted['question_deleted_' . $nQuestion])
					   || isset($recordsDeleted['participant_deleted_' . $nParticipant])) {
						if(isset($tables[$table.'s'][$nKey]))
							unset($tables[$table.'s'][$nKey]);
						continue;
					}

				}
				elseif($table == 'participant') {
					$ownedParticipants[$nKey] = true;
				}
				elseif($table == 'question') {
					$ownedQuestions[$nKey] = true;
				}
				if(!isset($recordsDeleted[$table . '_deleted_' . $nKey])) {
					$tables[$table.'s'][$nKey] = $value; 
					$recordsChanged[$table.'s'][$nKey] = true;
				}
			}
		}
		elseif($param == 'description'
		|| $param == 'title'){
			if(strpos($value, chr(226)) !== FALSE)// €
				$value=str_replace(chr(226), 'E', $value);
			$value = utf8_decode($value);
			
			$tables[$param] =  $value; 
		}
	}
	if($useCookies){
		$cookieTimeout = time()+60*60*24*30 * 2;
		setcookie('p'.$dataNodeId, implode(',', array_keys($ownedParticipants)), $cookieTimeout
				  , dirname($_SERVER["PHP_SELF"]).'/', $_SERVER["HTTP_HOST"]);
		setcookie('q'.$dataNodeId, implode(',', array_keys($ownedQuestions)), $cookieTimeout
				  , dirname($_SERVER["PHP_SELF"]).'/', $_SERVER["HTTP_HOST"]);
	}
		
		
	//archived		
	foreach($recordsArchived as $tableName => $table)
		if($table){
			$tables[$tableName . '_archived' ] = $table;
		}
		
	//save		
	$funcSetData = page::execute('data/set', $node);
	$funcSetData($dataNodeId, $tables, $user);

	return true;
};
?>