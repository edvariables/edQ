<?php
if(isset($argv)){
    foreach ($argv as $arg) {
        $e=explode("=",$arg);
        if(count($e)==2)
            $_REQUEST[$e[0]]=$e[1];
        else   
            $_REQUEST[$e[0]]=0;
    }
    $_REQUEST['cron']=true;
}
$_REQUEST['inneronly']=true;
$_REQUEST['edq-user'] = 'invite';
$_REQUEST['vw--legend'] = true;

include('index.php');
?>
