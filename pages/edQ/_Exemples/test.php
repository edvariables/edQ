<ul>Test
<li><?php
if(!isset($arguments))
	$arguments = array();
call_page(':sub', $arguments, __FILE__);
$arguments['test'] = date('now');
print_r($arguments);
?></ul>