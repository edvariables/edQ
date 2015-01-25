<fieldset><legend><h3>Favoris</h3></legend>
<?php page::execute(':Favoris', $node);?>
</fieldset>

<br/>
<fieldset><legend><h3>edQ : Réinitialiser l'ordre des onglets d'une page</h3></legend>
<i>utile lorsque le script contenu provoque une erreur avant l'affichage des onglets</i>
<?php page::execute(':reset tabs', $node);?>
</fieldset>
	
<br/>
<fieldset><legend><h3>Disposition des cadres de la page</h3></legend>
<?php page::execute(':Layout', $node);?>
</fieldset>