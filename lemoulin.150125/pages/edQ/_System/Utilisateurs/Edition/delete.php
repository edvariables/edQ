<?php
if(!isset($_REQUEST['d--IdContact']) || !$_REQUEST['d--IdContact'])
	return "référence manquante";

try {
	$currentUserType = $_SESSION['edq-user']['UserType'];
	$isCurrentUser = $_SESSION['edq-user']['id'] == $_REQUEST['d--IdContact'];
}
catch(Exception $ex){
	die( "Erreur de droits : " . $ex );
}
$node = node($node, __FILE__);
$db = get_db();

if($isCurrentUser)
	return "Il est interdit de se suicider !";

$search = 0;
if(isset($_REQUEST["d--IdContact"])){
	$search = (int)$_REQUEST["d--IdContact"];
	$isCurrentUser = $_SESSION['edq-user']['id'] == $search;
}
else if(isset($_REQUEST["f--IdContact"])){
	$search = (int)$_REQUEST["f--IdContact"];
	$isCurrentUser = $_SESSION['edq-user']['id'] == $search;
}
else {
	$search = $_SESSION['edq-user']['id']; //exemple
	$isCurrentUser = true;
}
if($search == '0' || $search == 'new'){
	$rows = array(array(
		'IdContact' => 'new'
		, 'Enabled' => '1'
		, 'UserType' => '64'
	));
	$search = false;
}
else {
	$rows = $db->all("
		SELECT c.IdContact, c.Name, u.Enabled, u.UserType
		FROM contact c
		JOIN user u
			ON c.IdContact = u.IdUser
		WHERE
			c.IdContact = ?
		LIMIT 1"
		, array( $search )
	);
}

if(count($rows) == 0){
	die ( "Aucun utilisateur pour $search" );
}

$row = $rows[0];
$currentUserType = $_SESSION['edq-user']['UserType'];
	
if($row['UserType'] <= $currentUserType)
	throw new Exception('Impossible de supprimer un utilisateur de ce niveau.');

//var_dump($_POST);

$db = get_db();

$sql = "DELETE FROM contact
		WHERE IdContact = ?";

$params = array();
$params[] = $_REQUEST['d--IdContact'];

$result = $db->query($sql, $params);

//var_dump( $result, $params );

$sql = "DELETE FROM user
		WHERE IdUser = ?";

$params = array();
$params[] = $_REQUEST['d--IdContact'];

$result = $db->query($sql, $params);

//var_dump( $result, $params );

echo 'Ok';
?>