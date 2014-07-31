<h1><?=isset($arguments) && isset($arguments['title']) ? $arguments['title'] : 'Sub-page'?></h1>
<?= __FILE__?>
<?php if(isset($arguments)) var_dump($arguments);?>