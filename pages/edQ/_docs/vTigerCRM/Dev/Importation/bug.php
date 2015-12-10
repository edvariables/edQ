<pre>
Erreur lors de l'import dûe à la pérésence d'une apostrophe dans le fieldname d'un champ obligatoire :
- modification de layouts/vlayout/modules/Import/ImportAdvanced.tpl
	{* ED150408 adds htmlspecialchars *}
	&lt;input type="hidden" id="mandatory_fields" name="mandatory_fields" value="{htmlspecialchars($ENCODED_MANDATORY_FIELDS)}" /&gt;
	

</pre>