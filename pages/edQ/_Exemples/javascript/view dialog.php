<a href="view.php?id=<?=1179?>"
 	 onclick="$.get(this.getAttribute('href'), function(html){
 	 	 $('<div></div>').appendTo('body').html(html).dialog({
	 	 	 title: 'Aperçu',
 	 	 	 width: 'auto',
 	 	 	 height: 'auto'
 	 	 });
 	 	});
	 return false;">Afficher</a>