<?php
try {
	$currentUserType = $_SESSION['edq-user']['UserType'];
	$isCurrentUser = $_SESSION['edq-user']['id'] == $_POST['d--IdContact'];
}
catch(Exception $ex){
	return "Erreur de droits : " . $ex;
}
if($_POST['d--user-UserType'] < $currentUserType)
	throw new Exception('Impossible d\'affecter ce niveau d\'utilisateur.');

//var_dump($_POST);

$db = get_db();

$insertInto = $_POST['d--IdContact'] == '0' || $_POST['d--IdContact'] == 'new';
if($insertInto){

	$sql = "INSERT INTO contact (Name, ShortName, EMail, Phone1)
		VALUES(?, ?, ?, ?)";

	$params = array();
	$params[] = $_POST['d--Name'];
	$params[] = $_POST['d--ShortName'];
	$params[] = $_POST['d--EMail'];
	$params[] = $_POST['d--Phone1'];

	$result = $db->query($sql, $params);
	
	$_REQUEST["d--IdContact"] = $_POST['d--IdContact'] = $db->insert_id();

}
else {
	
	$sql = "UPDATE contact
		SET Name = ?
		, ShortName = ?
		, EMail = ?
		, Phone1 = ?
		WHERE IdContact = ?";

	$params = array();
	$params[] = $_POST['d--Name'];
	$params[] = $_POST['d--ShortName'];
	$params[] = $_POST['d--EMail'];
	$params[] = $_POST['d--Phone1'];
	$params[] = $_POST['d--IdContact'];

	$result = $db->query($sql, $params);
}
//var_dump( $result, $params );

if($insertInto){

	$sql = "INSERT INTO user (IdUser, UserType, Enabled, Password)
		VALUES(?, ?, ?, PASSWORD(?))";

	$params = array();
	$params[] = $_POST['d--IdContact'];
	$params[] = $_POST['d--user-UserType'];
	if(isset($_POST['d--user-Enabled']))
		$params[] = $_POST['d--user-Enabled'];
	else
		$params[] = '1';
	if($_POST['d--user-Password']
	&& ($_POST['d--user-Password'] == $_POST['d--user-Password-confirm']))
		$params[] = $_POST['d--user-Password'];
	else
		$params[] = '';

	$result = $db->query($sql, $params);
}
else {
	$sql = "UPDATE user
		SET UserType = ?
		" . (isset($_POST['d--user-Enabled'])
			? ", Enabled = ?"
			  : ""
		) . "
		" . (($_POST['d--user-Password']
			 && ($_POST['d--user-Password'] == $_POST['d--user-Password-confirm']))
			 ? ', Password = PASSWORD(?)'
			 : '') . "
		WHERE IdUser = ?";

	$params = array();
	$params[] = $_POST['d--user-UserType'];
	if(isset($_POST['d--user-Enabled']))
		$params[] = $_POST['d--user-Enabled'];
	if($_POST['d--user-Password']
	&& ($_POST['d--user-Password'] == $_POST['d--user-Password-confirm']))
		$params[] = $_POST['d--user-Password'];
	$params[] = $_POST['d--IdContact'];

	$result = $db->query($sql, $params);
}
//var_dump( $result, $params );

node('..', $node, 'view');

?>