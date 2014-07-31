<ul class="sys-ui-layout">
<li><a href="" onclick="$('.ui-layout-pane').remove();
					myLayout.destroy();
					window.localStorage.setItem('edq-layout', '');">
	Réinitialiser le dimensionnement des cadres de la page</a>
</li>
	<br/>
<li><a class="addthemeswitcher"></a></li>
<li><a href="" onclick="removeUITheme(); return false;">
	supprimer le thême</a></li>
</ul>
<!-- theme switcher -->
<script type="text/javascript" src="js/debug.js"></script>
<script type="text/javascript" src="js/themeswitchertool.js"></script> 
<script type="text/javascript">

$(document).ready(function(){

	addThemeSwitcher('.sys-ui-layout .addthemeswitcher', { top: '13px', right: '50%' });
	$('#themeContainer').css({ 'position': 'static', 'overflow-x' : 'auto', 'display': 'inline-block' });
});
</script>
<style> 
	.sys-ui-layout ul { list-style: none; }
	.sys-ui-layout li { display: inline-block; padding: 1em 0; margin : 1em; 
		border: 2px outset gray; border-radius: 4px; }
	.sys-ui-layout li:hover { background-color: #F7F7F7; }
	.sys-ui-layout li:hover:active { border-style: inset; }
	.sys-ui-layout a { padding: 1em 2em; }
	.sys-ui-layout .addthemeswitcher { margin: 0; padding: 1em; }
</style>