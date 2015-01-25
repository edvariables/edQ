<h2>Comment définir le "label" par défaut d'un enregistrement d'une entité ?</h2>
<ul class="edq-doc">

		<li><pre>
A chaque enregistrement d'une entité, son nom est affecté au champ vtiger_crmentity.label 
C'est ce nom que l'on voit dans les champs d'entités liés.
Si il est mal défini, on peut ne pas voir qu'un enregistrement est lié à un autre.
		</pre></li>
	
		<li><pre>
Le champ qui sert de titre à un enregistrement est défini par 
table vtiger_entityname, champ fieldname
		</pre></li>

		<li><pre>
on peut composer le nom avec des virgules : <code>montant,periodicite,sepadatesignature</code>
		</pre></li>

		<li><pre>
On peut aussi surclasser %ModuleName%/models/Module.php, function getNameFields
<code>return array('montant', 'periodicite')</code>
		</pre></li>

		<li><pre>
On peut aussi le faire dans le .tpl
<code>layouts\vlayout\modules\%ModuleName%\DetailViewHeaderTitle.tpl</code>
mais ça ne permet pas la gestion propre du label par défaut de l'entité.
		</pre></li>
</ul>
			