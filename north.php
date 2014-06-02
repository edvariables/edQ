<a href="bin/logout.php">déconnexion</a>
<button onClick="removeUITheme()">supprimer le thême</button> &nbsp; &nbsp;
<button onClick="myLayout.resizeAll(); myLayout.sizeContent('center');">redimmensionner</button>

<!-- theme switcher -->
<script type="text/javascript" src="js/debug.js"></script>
<script type="text/javascript" src="js/themeswitchertool.js"></script> 
<script type="text/javascript">

$(document).ready(function(){

			addThemeSwitcher('.ui-layout-north',{ top: '13px', right: '20px' });

});
</script>