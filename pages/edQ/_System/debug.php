<?php
$uid = uniqid('debug');
?><form id="<?=$uid?>">
<fieldset><legend>Afficher des informations de débuggage</legend>
<ul>
	<li><a href="<?=url_view(tree::get_id_by_name('session', $node))?>"
   onclick="return dialog.call(this);">Session</a>
	<li><a href="<?=url_view(tree::get_id_by_name('server', $node))?>"
   onclick="return dialog.call(this);">Serveur</a>
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
<style> 
	#<?=$uid?> ul { list-style: none; }
	#<?=$uid?> li { display: inline-block; padding: 1em 0; margin : 1em; 
		border: 2px outset gray; border-radius: 4px; }
	#<?=$uid?> li:hover { background-color: #F7F7F7; }
	#<?=$uid?> li:hover:active { border-style: inset; }
	#<?=$uid?> a { padding: 1em 2em; }
</style>