<pre>
<code>$smarty->display(vtlib_getModuleTemplate($currentModule, 'MyListview.tpl'));</code>
</pre>



<h4>Attention</h4>
<p style="white-space:pre;">	
	Rappel : Attention au javascript dans smarty, ça peut boguer facilement avec des messages incompréhensibles :
	- pas de // mais /* ok */ ou {* ok *}
	- des espaces autours des { } pour qu'il n'y ai pas de confusion avec {$SMARTY}
	
</p>