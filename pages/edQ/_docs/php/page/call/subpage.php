<ul>Sub
<li><?php
echo 'node isset : '; var_dump(isset($node));
echo 'set node <br>'; $node = page::node($node);
echo 'node isset : '; var_dump(isset($node));
$arguments[':sub'] = date('now');
print_r($arguments);
//echo ('node, sortie de subpage :');
//var_dump($node);
?></ul>