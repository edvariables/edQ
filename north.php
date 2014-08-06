<?php
$design = is_design();
?><a href="bin/logout.php">déconnexion</a>
<a href="bin/user.php" onclick="$('<div></div>').load(this.href).dialog({ 'modal':  true, 'width' : 'auto', 'height' : 'auto' });
	return false;"><?=$_SESSION['edq-user']['Name']?></a>
<?php if(user_right('design')){
?><a href="index.php<?=$design ? '' : '?design=1'?>"><?=$design ? 'sans design' : 'mode design'?></a><?php
}?>
<a href="index.php<?=$design ? '?design=1' : ''?>">rafraîchir</a>

