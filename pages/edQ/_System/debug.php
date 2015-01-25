<?php
$uid = uniqid('debug');
?><form id="<?=$uid?>">
<fieldset><legend>Afficher des informations de débuggage</legend>
<ul class="edq-buttons">
	<li><a href="<?=page::url(':session', $node)?>"
   onclick="return dialog.call(this);">Session</a>
	<li><a href="<?=page::url(':server', $node)?>"
   onclick="return dialog.call(this);">Serveur</a>
	<li><a href="<?=page::url(':phpinfo', $node)?>"
   onclick="return dialog.call(this);">Php info</a>
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