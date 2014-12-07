<?php
$design = is_design();
?><a href="inc/logout.php">déconnexion</a>

<a href="view.php?id=/_System/Utilisateurs/Edition" onclick="$('<div></div>').load(this.href).dialog({ 'modal':  true, 'width' : 'auto', 'height' : 'auto' });
	return false;"><?=$_SESSION['edq-user']['Name']?></a>
<?php
if(user_right('design')){
    ?><a href="index.php<?=$design ? '' : '?design=1'?>"><?=$design ? 'sans design' : 'mode design'?></a><?php
}
?>
<a href="index.php<?=$design ? '?design=1' : ''?>">rafraîchir</a>

