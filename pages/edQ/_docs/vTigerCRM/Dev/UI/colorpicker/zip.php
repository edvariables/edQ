<a href="<?=preg_replace('/^(HTTPS?).*$/', '$1', $_SERVER['SERVER_PROTOCOL'])?><?php
	?>://<?=$_SERVER['SERVER_NAME']?><?php
	?>:<?=$_SERVER['SERVER_PORT']?><?php
	?><?=dirname(page::file_url($node, __FILE__))?>/vtiger.colorpicker.zip">vtiger.colorpicker.zip</a>
