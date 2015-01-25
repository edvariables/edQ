<a title="déplacer ici un élément"
   href="#"
	onclick="var $dom = $('#favpanel');
			$.post('view.php?id=/_System/Utilisateur/Preferences/set', { 
				domain : $.jstree.defaults.favpanel.userpref_domain,
				param : $dom[0].tagName + '#' + $dom.attr('id'),
				value : '{}',
				'get' : false
			});
			 return false;">
	<span class="ui-icon ui-icon-trash" style="display: inline-block; font-style: none;"> </span>
	effacer les icônes de la barre de favoris</a> <i>
<br>vous devez, ensuite, recharger la page web</i>
