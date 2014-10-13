<?php

$_REQUEST['inneronly']=true;
$_REQUEST['edq-user'] = 'invite';
$_REQUEST['vw--legend'] = true;
if($_REQUEST['id'] && !is_numeric($_REQUEST['id'])
   && $_REQUEST['id'][0] != '/')
        $_REQUEST['id'] = '/' . $_REQUEST['id'];
include('index.php');
?>