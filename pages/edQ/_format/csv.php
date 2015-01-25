<?php
$uid = uniqid('doc');
$node = node($node, __FILE__);
?><form id="<?=$uid?>">
<fieldset class="ui-widget-content ui-corner-all">
	<legend class="ui-state-default ui-corner-all">Convertir en .csv </legend>
<ul class="edq-buttons">
	<li><a href="<?=page::url(':from rows', $node)?>"
   onclick="return dialog.call(this);">rows : télécharger les données issues d'un tableau</a>
	<li><a href="<?=page::url(':from html', $node)?>"
   onclick="return dialog.call(this);">table : télécharger les données issues du html contenant la &lt;table&gt;</a>
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