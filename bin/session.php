<?php
session_start();

if(!isset($_SESSION['edq-user']) || !isset($_SESSION['edq-user']['id']) || $_SESSION['edq-user']['id'] === ''){ 

	die('<script type="text/javascript">window.location = "bin/login.php"; </script>');

}

?>