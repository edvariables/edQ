<?php
$uid = uniqid('debug');
?><form id="<?=$uid?>">
<fieldset><legend>Documentation <?=$node['nm']?></legend>
<ul class="edq-buttons"><?php
	
// sous-pages
$pages = $tree->get_children($node);
foreach($pages as $child)
	if($path['nm'][0] != '_'){ //commence pas par '_'
		?><li><a href="<?=page::url($child)?>"
   		onclick="return dialog.call(this);"><?=$child['nm']?></a><?php
}?>
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
?>