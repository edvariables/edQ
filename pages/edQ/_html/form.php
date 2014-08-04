<?php
$test = isset($arguments) && isset($arguments['f--test'])
	? $arguments['f--test']
	: (isset($_REQUEST) && isset($_REQUEST['f--test'])
	   ? $_REQUEST['f--test']
	   : '');
if($test)
	var_dump($test);
$uid_form = uniqid('form');
?>
<form id="<?=$uid_form?>" method="POST" action="<?= page::url( $node )?>" autocomplete="off">
<fieldset><legend><?= $node['nm'] ?></legend>
	test : <input size="32" value="<?= htmlspecialchars($test, ENT_QUOTES) ?>" name="f--test"/>
	<input type="submit" value="Valider" style="margin-left: 2em;"/>
</fieldset></form>
<?= isset($view) ? $view->searchScript($uid_form) : '$view no set'?>