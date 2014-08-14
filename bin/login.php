<?php
/*
Simple ajax live login script by http://www.asif18.com/19/jquery/ajax-login-form-using-jquery-and-php/
*/

$loginError = '';
session_start();
$loginUser = isset($_SESSION['edq-user'])
	? (isset($_SESSION['edq-user']['ShortName']) && $_SESSION['edq-user']['ShortName'] != null
		? $_SESSION['edq-user']['ShortName']
		: $_SESSION['edq-user']['Name'])
	: (isset($_SERVER['REDIRECT_REMOTE_USER'])
		? $_SERVER['REDIRECT_REMOTE_USER']
		: '');

// Check
if(isset($_POST['action']) && $_POST['action'] == 'login'){ 
	require_once('../conf/edQ.conf.php');
	require_once('class.db.php');
	function encrypt($string){
		return base64_encode(base64_encode(base64_encode($string)));
	}
	
	function decrypt($string){
		return base64_decode(base64_decode(base64_decode($string)));
	}
	$username 		= htmlentities($_POST['username']); // Get the username
	$password 		= $_POST['password']; // Get the password and decrypt it
	$query			= '
		SELECT c.IdContact AS id, c.Name, c.ShortName, u.UserType
		FROM user u
		JOIN contact c
			ON c.IdContact = u.IdUser
		WHERE (c.Name = ? OR c.ShortName = ?)
		AND u.Password = PASSWORD( ? )
	'; 
	$params			= array($username, $username, $password);
	$db = db::get(DBTYPE . '://' . DBUSER . ':' . DBPASSWORD . '@' . DBSERVER . ':' . DBPORT . '/' . DBNAME);
	$rows = $db->all($query, $params);
	if(count($rows) <= 0 || count($rows) > 1){ // If no users exist with posted credentials print 0 like below.
		if(isset($_SESSION['edq-user']))
			unset($_SESSION['edq-user']['id']);
		$loginError = '<span class="error">Utilisateur ou mot de passe incorrect !</span>';
	} else {
		if(!isset($_SESSION['edq-user']))
			$_SESSION['edq-user'] = array();
		foreach($rows[0] as $colName => $value)
			$_SESSION['edq-user'][$colName] = $value;
	}
	
	$query			= '
		SELECT r.Domain, r.Rights
		FROM rights r
		WHERE r.UserType = ?
	'; 
	$params			= array((int)$_SESSION['edq-user']['UserType']);
	$db = db::get(DBTYPE . '://' . DBUSER . ':' . DBPASSWORD . '@' . DBSERVER . ':' . DBPORT . '/' . DBNAME);
	$rows = $db->all($query, $params);
	if(count($rows) <= 0){ // If no users exist with posted credentials print 0 like below.
		if(isset($_SESSION['edq-user']))
			unset($_SESSION['edq-user']['id']);
		$loginError = '<span class="error">Vous n\'êtes pas autorisé à vous connecter !</span>';
	} else {
		if(!isset($_SESSION['edq-user']['rights']))
			$_SESSION['edq-user']['rights'] = array();
		foreach($rows as $row)
			$_SESSION['edq-user']['rights'][$row['Domain']] = (int)$row['Rights'];
	}
}

if(isset($_SESSION['edq-user']) && isset($_SESSION['edq-user']['id']) && $_SESSION['edq-user']['id'] != ''){ // Redirect to secured user page if user logged in
	die( '<script type="text/javascript">window.location = "../index.php"; </script>' );
}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>edQ - Connexion</title>
<script type="text/javascript" src="../js/jquery.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	// Focus to the username field on body loads
	if($('#username').val() && !$('#password').val())
		$('#password').focus().select(); 
	else
		$('#username').focus().select(); 
	$('#login').click(function(){ // Create `click` event function for login
		var username = $('#username'); // Get the username field
		var password = $('#password'); // Get the password field
		var login_result = $('.login_result'); // Get the login result div
		login_result.html('chargement..'); // Set the pre-loader can be an animation
		if(username.val() == ''){ // Check the username values is empty or not
			username.focus(); // focus to the filed
			login_result.html('<span class="error">Merci de saisir votre nom d\'utilisateur</span>');
			return false;
		}
		if(password.val() == ''){ // Check the password values is empty or not
			password.focus();
			login_result.html('<span class="error">Merci de saisir votre mot de passe</span>');
			return false;
		}
		return true;
	});
});
</script>
<style type="text/css">
body{
	margin: 0;
	padding: 0;
	font-family: arial;
	color: #2C2C2C;
	font-size: 14px;
	background-color: #F4F4F4;
}
h1 a{
	color:#2C2C2C;
	text-decoration:none;
}
h1 a:hover{
	text-decoration:underline;
}
.as_wrapper{
	margin: 3em auto;
	width: 1000px;
	height: 20em;
}
.mytable{
	margin: 0 auto;
	padding: 20px;
	border:2px outset #17A3F7;
	background-color: white;
}
.as_login_heading{
	margin:0;
	padding:0;
	font-weight:bold;
	text-align:left;
	font-size:18px;
}
.success{
	color:#009900;
}
.error{
	color:#F33C21;
}
.talign_left{
	text-align:left;
}
.as_input{
	border:0;
	outline:0;
	margin:0;
	padding:0;
	padding:5px;
	width:180px;
	border:#0081b5 solid 1px;
}
.as_input:hover{
	border:#321363 solid 1px;
}
.as_input.error{
	border:#ed1846 solid 1px;
}
.as_button{
	background:#339966;/*#ed1846;*/
	border:none;
	outline:none;
	margin:0;
	padding:0;
	color:#FFF;
	padding:5px 10px;
	font-weight:300;
	cursor:pointer;
}
.as_button:hover{
	background:#238956;
}
.as_button:active{
	background:#a1147d;
}
.login_result{
	display:block;
	width:100%;
	text-align:center;
	height:25px;
}
</style>
</head>

<body>
<div class="as_wrapper">

<form action="?" method="post">
<input type="hidden" name="action" value="login"/>
<table class="mytable">
<tr>
	<td colspan="2"><h3 class="as_login_heading">Connexion</h3></td>
</tr>
<tr>
	<td colspan="2"><div class="login_result" id="login_result"><?=$loginError?></div></td>
</tr>
<tr>
	<td>Utilisateur</td>
    <td><input type="text" name="username" id="username" class="as_input" value="<?=$loginUser?>" /></td>
</tr>
<tr>
	<td>Mot de passe</td>
    <td><input type="password" name="password" id="password" class="as_input" /></td>
</tr>
<tr>
	<td></td>
</tr>
<tr>
	<td colspan="2"><input type="submit" name="login" id="login" class="as_button" value="Login &raquo;" /></td>
</tr>
</table>
</form>
</div>
</body>
</html>