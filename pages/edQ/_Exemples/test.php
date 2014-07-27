<?php

$perms = fileperms( sys_get_temp_dir() );
$info = (string)$perms;
$info .= (($perms & 0x0100) ? 'r' : '-');
$info .= (($perms & 0x0080) ? 'w' : '-');

echo($info);
?>