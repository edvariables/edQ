<pre>
<?= __FILE__ ?>

file_url : <?php var_dump(node($node, __FILE__, 'file_url'))?>

<?php var_dump(node($node, __FILE__, 'page'))?>

':sub page'
<?php var_dump(node(':sub', $node, 'page'))?>

':sub call'
<?php var_dump(node(':sub', $node, 'call'))?>

</pre>