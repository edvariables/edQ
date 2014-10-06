<?php
session_start();

if(!isset($_SESSION['edq-user']) || !isset($_SESSION['edq-user']['id']) || $_SESSION['edq-user']['id'] === ''){ 

	if($_REQUEST['edq-user']=='invite'){
		$_SESSION['edq-user'] = array(
			'id' => 0
			, 'name'=> 'InvitŽ'
			, 'rights' => array());
	}
	else
		die('<script type="text/javascript">window.location = "inc/login.php"; </script>');

}

?>