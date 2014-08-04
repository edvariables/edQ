<?php
$uid = uniqid('debug');
?><form id="<?=$uid?>">
<fieldset><legend>Convertir en .csv </legend>
<ul class="edq-buttons">
	<li><a href="<?=page::url(':rows', $node)?>"
   onclick="return dialog.call(this);">rows : retourne les données</a>
	<li><a href="<?=page::url(':table', $node)?>"
   onclick="return dialog.call(this);">table : retourne le html de la table</a>
</ul></fieldset>
</form>
<script>
	function dialog(){
		$.get(this.getAttribute('href'), function(html){
			$('<pre></pre>').appendTo('body').html(html).dialog({
				title: this.textContent,
				width: 'auto',
				height: 'auto'
			});
		})
		return false;
	}
</script>