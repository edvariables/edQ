<ul>Sub
<li><?php
echo 'node : '; var_dump(isset($node));

var_dump(node($node));

$arguments[':sub'] = date('now');
print_r($arguments);
?></ul>