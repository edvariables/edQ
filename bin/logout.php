<?php
session_start();
if(isset($_SESSION['edq-user']))
	unset($_SESSION['edq-user']['id']);
die('<script type="text/javascript">window.location = "login.php"; </script>');
?>