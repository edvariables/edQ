<?php
$design = isDesign();
?><button onClick="removeUITheme()">supprimer le thême</button> &nbsp; &nbsp;
<button onClick="myLayout.resizeAll(); myLayout.sizeContent('center');">redimmensionner</button>

<a href="bin/logout.php">déconnexion</a>
<a href="bin/user.php" onclick="$('<div></div>').load(this.href).dialog({ 'modal':  true, 'width' : 'auto', 'height' : 'auto' });
	return false;"><?=$_SESSION['edq-user']['Name']?></a>
<?php if(userRight('design')){
?><a href="index.php<?=$design ? '' : '?design=1'?>"><?=$design ? 'sans design' : 'mode design'?></a><?php
}?>
<a href="index.php<?=$design ? '?design=1' : ''?>">rafraîchir</a>

<!-- theme switcher -->
<script type="text/javascript" src="js/debug.js"></script>
<script type="text/javascript" src="js/themeswitchertool.js"></script> 
<script type="text/javascript">

$(document).ready(function(){

			addThemeSwitcher('.ui-layout-north',{ top: '13px', right: '20px' });

});
</script>