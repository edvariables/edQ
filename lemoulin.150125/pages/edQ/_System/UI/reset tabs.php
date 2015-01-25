<ul class="sys-ui-layout">
	<li><a href="tree/nodeViewer/viewers.php?vw=nodeViewer_viewers&op=submit&viewers-sort|=[]&id="
		   onclick="var node_id; if(!(node_id = prompt('Id de la page ?'))) return false;;
				$.get(this.href + node_id);
		$(this.parentNode).after('<div>Ok</div>');
				return false;">
	Réinitialiser l'ordre des onglets d'une page</a>
</li>
	<br/>
</ul>
<style> 
	.sys-ui-layout ul { list-style: none; }
	.sys-ui-layout li { display: inline-block; padding: 1em 0; margin : 1em; 
		border: 2px outset gray; border-radius: 4px; }
	.sys-ui-layout li:hover { background-color: #F7F7F7; }
	.sys-ui-layout li:hover:active { border-style: inset; }
	.sys-ui-layout a { padding: 1em 2em; }
	.sys-ui-layout .addthemeswitcher { margin: 0; padding: 1em; }
</style>