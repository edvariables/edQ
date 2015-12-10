<pre>
Un champ obligatoire dont le nom (traduit) comporte une apostrophe provoque divers plantages.
A l'import, par exemple, mais pas que.
J'ai remplacé 
value='{$ENCODED_MANDATORY_FIELDS}'
par 
value="{htmlspecialchars($ENCODED_MANDATORY_FIELDS)}"
</pre>