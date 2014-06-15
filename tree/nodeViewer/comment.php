<?php /* Gestion du champ 'comment' d'un noeud via la table node_comment
UTF8 é
*/
if(isset($_POST['operation'])
&& $_POST['operation'] == 'submit') {
	require_once(dirname(__FILE__) . '/../../conf/db.conf.php');
	require_once(dirname(__FILE__) . '/../../bin/class.db.php');
					
	$db = db::get(DBTYPE . '://' . DBUSER . ':' . DBPASSWORD . '@' . DBSERVER . ':' . DBPORT . '/' . DBNAME);
	if($_POST['value'] === '')
		$q = $db->query("DELETE FROM node_comment"
			. " WHERE id = ?"
			, array($_POST['id'])
		);
	else
		$q = $db->query("INSERT INTO node_comment (id, value)"
			. " VALUES (?, ?)"
			. " ON DUPLICATE KEY UPDATE"
			. " value = ?"
			, array($_POST['id'], $_POST['value'], $_POST['value'])
		);
	die((string)$q->af());
}


class nodeViewer_comment extends nodeViewer {
	public $name = 'comment';
	public $text = 'Commentaires';
	
	public function html($node){
		// instance de node
		$node = node::fromClass($this->domain, $node);
	
		$db = db::get(DBTYPE . '://' . DBUSER . ':' . DBPASSWORD . '@' . DBSERVER . '/' . DBNAME);
		$comments = $db->one("
			SELECT d.value
			FROM 
				node_".$this->name." d
			WHERE 
				d.id = ?"
			, array((int)$node->id)
		);
		$exists = $comments !== null;
		if(!$exists)
			$comments = '';

		$uid = uniqid('form-');
		return array(
			"title" => $node->label()
			, "content" => '<form id="' . $uid . '" method="post" action="' . $this->get_url($node) . '">'
					. '<input type="hidden" name="id" value="' . $node->id . '"/>'
					. '<input type="hidden" name="vw" value="' . __CLASS__ . '"/>'
					. '<input type="hidden" name="operation" value="submit"/>'
					. '<fieldset>'
					. ($exists ? '' : '<legend><small><i>(le commentaire n\'existe pas)</i></small></legend>')
					. '<textarea name="value" style="width:100%;" rows="14">' . htmlspecialchars( $comments ) . '</textarea>'
					. '</fieldset>'
					. '<fieldset>'
					. '<input type="submit" value="Enregistrer"/>'
					. '</fieldset>'
					. '</form>'
				. $this->formScript($uid)
		);
	}
}

?>