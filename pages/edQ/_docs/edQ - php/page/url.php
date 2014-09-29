<?php
$uid = uniqid('form');
?>
<ul id="<?=$uid?>" class="edq-buttons">
	<li><a href="<?= page::url($node) ?>">recharger</a></li>
	<li><a href="<?= page::url($node, null, null, 'file.content') ?>">contenu</a></li>
	<li><a href="<?= page::url($node, null, null, 'comment') ?>">commentaires</a></li>
	
	<li><a href="<?= page::url('arguments', $node, array("from" => $node['nm']) ) ?>">arguments</a></li>
	
	<li><a href="<?= page::url('/_System/Arborescence/Noeuds', $node) ?>">/_System/Arborescence/Noeuds</a></li>
	<li><a href="<?= page::url('/_System/Arborescence/Recherche dans les fichiers', $node) ?>"><i>Recherche dans les fichiers</i></a></li>
	
	<li><a href="< ?= /*page::url('..html/table/rows', $node)*/ ? >">..html/table/rows (TODO)</a></li>
	
	<li><a href="<?= page::url('..', $node) ?>">..</a></li>
	<li><a href="<?= page::url('..page', $node) ?>">..page</a></li>
	<?php $url = page::url('.._Exemples', $node) ?>
	<li><a href="<?= $url ?>">.._Exemples</a></li>
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