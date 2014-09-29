<?php
$node = node($node);
$uid = uniqid('ul');
?><ul id="<?=$uid?>"><h1>edQ - éléments du langage php</h1>
	<li><a href="<?=node(':node', $node, 'url')?>"><code>node</code></a></li>
	<li><a href="<?=node(':page', $node, 'url')?>"><code>page</code></a></li>
	<li><a href="<?=node(':tree', $node, 'url')?>"><code>tree</code></a></li>
	<li><a href="<?=node(':helpers', $node, 'url')?>"><code>helpers</code></a></li>
</ul>
<script>
	$().ready(function(){
		$("#<?=$uid?> a")
			.click(function(){
				$.get(this.href, function(html){
					$('<div></div>')
						.appendTo(document.body)
						.html(html)
						.dialog({
							width: 'auto',
							height: 'auto'
						});
				});
				return false;
			});
	});
</script>