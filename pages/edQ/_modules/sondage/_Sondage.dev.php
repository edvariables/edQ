<pre><?php
$node = node($node, __FILE__);
$uid = uniqid('form');
if(!isset($arguments))
	$arguments = $_REQUEST;
$homeNode = isset($arguments) && $arguments['homeNode']
	? $arguments['homeNode']
	: $node;
$dataNodeId = isset($arguments) && $arguments['dataNodeId']
	? $arguments['dataNodeId']
	: node(':data', $node, 'id');
$reponseNodeId = isset($arguments) && $arguments['reponseNodeId']
	? $arguments['reponseNodeId']
	: node(':reponse', $node, 'id');
//var_dump($_SERVER);

$user = isset($_SESSION) && isset($_SESSION['edq-user']) && isset($_SESSION['edq-user']['rights']) ? $_SESSION['edq-user'] : false;

$isAdmin = $user && $user['UserType'] <= 4;
$useCookies = !$isAdmin;

if($useCookies){
	//participants créés depuis cet ordi
	$ownedParticipants = isset($_COOKIE) && isset($_COOKIE['p'.$dataNodeId]) ? explode(',', $_COOKIE['p'.$dataNodeId]) : array();
	if($ownedParticipants)
		$ownedParticipants = array_combine($ownedParticipants, $ownedParticipants);

	//questions créées depuis cet ordi
	$ownedQuestions = isset($_COOKIE) && isset($_COOKIE['q'.$dataNodeId]) ? explode(',', $_COOKIE['q'.$dataNodeId]) : array();
	if($ownedQuestions)
		$ownedQuestions = array_combine($ownedQuestions, $ownedQuestions);
}
else {
	$ownedParticipants = array();
	$ownedQuestions = array();
}

//Affichage des seuls éléments supprimés
$deletedData = isset($arguments) && $arguments['deletedData']
	? $arguments['deletedData']
	: false;

//données sources
$funcGetData = page::execute(':data/get', $node);
$tables = $funcGetData($dataNodeId);
if(!$tables)
	return;

$recordsChanged = array();
$recordsArchived = array();
//Le retour du submit
$funcSubmit = page::execute(':submit', $node);
if($funcSubmit){
	$funcSubmit($dataNodeId, $user, $isAdmin, $useCookies, $tables, $recordsChanged, $ownedQuestions, $ownedParticipants);
	
	//reload
	$tables = $funcGetData($dataNodeId);
	if(!$tables)
		return;
}
else {
	$tablesNew = false;
}
	
//var_dump($tables);

?>
</pre>
<form id="<?=$uid?>" method="POST" action="<?=page::url( $homeNode )?>" autocomplete="off">
<?php if($isAdmin){ ?><input type="hidden" name="full-data" value="1"/><?php }?>
<input type="hidden" name="submit" value="1"/>
<table colspan="0">
	<caption><?php if($isAdmin){
		?><div><?php
		?><input name="title" value="<?=isset($tables['title']) ? utf8_encode(html_entity_decode($tables['title'])) : ''?>"/><?
		?><br><textarea name="description"><?=isset($tables['description']) ? utf8_encode(html_entity_decode($tables['description'])) : ''?></textarea><?
		?></div><?php
	} else {
		?><?=isset($tables['title']) ? utf8_encode(html_entity_decode($tables['title'])) : ''?><?php
		?><br><?=isset($tables['description']) ? utf8_encode(html_entity_decode($tables['description'])) : ''?><?php
	}?>
	</caption>
	<thead><tr>
		<th><a class="submit" title="Enregistrer">
			<span class="ui-icon ui-icon-disk">&nbsp;</span></a>
			<a class="add-participant" href="#">
				<span class="ui-icon ui-icon-plus">&nbsp;</span>
				ajouter un participant</a></th>
		<?php
		foreach($tables["participants"] as $nParticipant => $participant){
			?><th class="participant" participant="<?=$nParticipant?>"><?php
				//var_dump($nParticipant, isset($ownedParticipants[$nParticipant]));
				if($isAdmin
				|| isset($recordsChanged["participants"][$nParticipant])
				|| isset($ownedParticipants[$nParticipant])
				  ){
					?><input name="participant_<?=$nParticipant?>" value="<?=$participant?>"/><?php
						?><a class="remove-participant" href="#" title="supprimer ce participant"><span class="ui-icon ui-icon-trash">&nbsp;</span></a><?php
				}
				else {
					?><?=$participant?><?php
				}
			?></th>
		<?php }
		?>
		<th class="score">Score</th>
		
	</thead>
	<tbody>
		<?php
		//cellules
		foreach($tables["scores"] as $nQuestion => $score){
			$question = $tables["questions"][$nQuestion];
			if(isset($tables["questions_archived"])
			&& array_key_exists($nQuestion, $tables["questions_archived"]))
				continue;
			?><tr class="question" question="<?=$nQuestion?>">
				<th class="texte"><?php
						if($isAdmin
						|| isset($recordsChanged["questions"][$nQuestion])
						|| isset($ownedQuestions[$nQuestion])
						){
							?><textarea name="question_<?=$nQuestion?>"><?=$question?></textarea><?php
						}
						else {
							?><pre><?=$question?></pre><?php
						}?></th>
				<?php
				foreach($tables["participants"] as $nParticipant=>$participant){
				?><td participant="<?=$nParticipant?>"><?php
					$nReponse = 'q'.$nQuestion.'_p'.$nParticipant;
					$reponse = $tables["reponses"][$nReponse];
					if(!is_numeric($reponse))
						$reponse = '';
					if($isAdmin
					|| isset($recordsChanged["reponses"][$nReponse])
					|| isset($ownedParticipants[$nParticipant])){
							?><input name="reponse_<?=$nReponse?>" value="<?=$reponse?>"/><?php
					}
					else {
						echo $reponse;
					}
				?></td><?php
				}
				?><th class="score"><?=$score?><?php
					if($isAdmin
					|| isset($recordsChanged["questions"][$nQuestion])
					|| isset($ownedQuestions[$nQuestion])){
						?><a class="remove-question" href="#" title="supprimer cette question"><span class="ui-icon ui-icon-trash">&nbsp;</span></a><?php
				}?></th>
			</tr>
		<?php }
		?>
	</tbody>
	<tfoot>
		<tr>
		<th><a class="add-question" href="#">
			<span class="ui-icon ui-icon-plus">&nbsp;</span>
			ajouter une question</a>
			<input type="submit" value="Enregistrer"/></th>
		</tr>
	</tfoot>
</table>
</form>
<?= page::form_submit_script($uid)?>
<?php
//cellules
if(isset($tables["questions_archived"])){
	echo '<ul>Archives';
	foreach($tables["questions_archived"] as $nQuestion => $date){
		$question = $tables["questions"][$nQuestion];
		echo '<li>';
		echo $date . ' : ' . htmlentities($question);
		echo '</li>';
	}
	echo '</ul>';
}
node(':css', $node, 'call', array('uid' => $uid));
$funcGetReponseValues = page::execute(':reponse/get', $node);
node(':js', $node, 'call', array(
			'uid' => $uid,
			'isAdmin' => $isAdmin,
			'reponseValues' => $funcGetReponseValues($reponseNodeId)
		));?>
<?php
?>