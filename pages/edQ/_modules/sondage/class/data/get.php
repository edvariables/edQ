<?php
return function($dataNodeId){
	$node = node($node, __FILE__);
	//données sources
	$tables = node($dataNodeId, $node, 'content');
	if(!$tables)
		$tables = array();
	else {
		if($tables[0] == '{')
			$tables = json_decode($tables, true);
		else
			eval('$tables = ' . $tables . ';');
		if(!is_array($tables)){
			?><code>Attention : problème de structure de données.
			<?=node(':data', $node, 'content')?></code><?php
			return false;
		}
	}
	//normalisation
	if(!array_key_exists("questions", $tables))
	   $tables["questions"] = array();
	if(!array_key_exists("reponses", $tables))
	   $tables["reponses"] = array();
	if(!array_key_exists("participants", $tables))
	   $tables["participants"] = array();

	
	//calcul des totaux pour pouvoir trier les questions
	$sortedQuestions = array();
	foreach($tables["questions"] as $nQuestion => $question){
		$score = 0;
		foreach($tables["participants"] as $nParticipant=>$participant){
			$nReponse = 'q'.$nQuestion.'_p'.$nParticipant;
			$reponse = $tables["reponses"][$nReponse];
			if(is_numeric($reponse)){
				$score += $reponse;
			}
		}
		$sortedQuestions["$nQuestion"] = $score;
	}
	//tri
	arsort($sortedQuestions);
	$tables["scores"] = $sortedQuestions;

	//returns
	return $tables;
};
?>