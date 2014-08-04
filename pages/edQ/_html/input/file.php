<?php
if(isset($_FILES) && isset($_FILES['q--filename'])){
	echo '<pre>' . htmlentities(file_get_contents($_FILES['q--filename']['tmp_name'])) . '</pre>';
}
$uid = uniqid('form');
?>
<form id="<?=$uid?>" method="POST" enctype="multipart/form-data"
	  action="<?=page::url( $node )?>" autocomplete="off">
	<input type="file" name="q--filename"/>
	<br/>
	<input type="submit" value="Envoyer"/>
</form>
<?= isset($view) ? $view->searchScript($uid) : '$view no set'?>