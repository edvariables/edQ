<?php
$uid = uniqid('form');
?>
<ul id="<?=$uid?>">
	<li><a href="<?= url_view($node) ?>">recharger</a></li>
	<li><a href="<?= url_view($node, null, 'file.content') ?>">contenu</a></li>
	<li><a href="<?= url_view($node, null, 'comment') ?>">commentaires</a></li>
</ul>
<script>
	$().ready(function(){
		$('#<?=$uid?> a').click(function(){
			var title = this.textContent + ' : ' + this.href;
			$('<div></div>')
				.appendTo('body')
				.load(this.href, function(){
					$(this).dialog({
						title: title
						, width: 'auto'
						, height: 'auto'
					})
				})
			;
			return false;
		})
	});
</script>