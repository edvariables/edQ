<?php
$uid = uniqid('debug');
$node = node($node, __FILE__);
?><form id="<?=$uid?>">
<fieldset class="ui-widget-content ui-corner-all">
	<legend class="ui-state-default ui-corner-all">Convertir un tableau de données (rows)</legend>
<ul class="edq-buttons">
	<li><a href="<?=page::url(':to html', $node)?>"
   onclick="return dialog.call(this);">to html : retourne le code html de représentation</a>
	<li><a href="<?=page::url(':to html/dataTable', $node)?>"
   onclick="return dialog.call(this);">to html/dataTable</a>
	<li><a href="<?=page::url(':from html/test', $node)?>"
   onclick="return dialog.call(this);">from html : retourne le tableau de données analysé depuis le code html contenant la balise &lt;table&gt;.</a>
</ul></fieldset>
</form>
<script>
	function dialog(options){
		var title = this.textContent;
		$.get(this.getAttribute('href'), function(html){
			$('<pre></pre>').appendTo('body').html(html).dialog($.extend({
				title: title,
				width: 'auto',
				height: 'auto'
			}, options));
		})
		return false;
	}
</script>