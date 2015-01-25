<h2>table <var>Paramètres de la table vtiger_field</var></h2>
<table>
	<tr><td>tabid</td><td><pre>id de la table</pre></td>
	<tr><td><u>fieldid</u>
		<br>(auto-increment)</td><td><pre>id du champ de la table</pre></td>
		
	<tr><td>displaytype</td><td><pre>affichage
		<ul><li>1 - Champ présent à la fois dans Detail view et Edit view </li>
    	 	<li>2  - Champ présent seulement dans Detail view (Ex. Created time, Modified time) </li>   	 
			<li>3  - Champ absent de Detail view et Edit view, mais qui peut apparaitre dans List view. Le champ n'est alors plus rattaché à un bloc</li>
			<li>4 - Champ de mot de passe </li>	
		</ul>
		</pre></td>
	<tr><td>quickcreate</td><td><pre>en création rapide
			<ul><li>0 - Le champ est obligatoire dans QuickCreate</li>
    	 		<li>1 - Le champ n'apparait pas dans QuickCreate</li>   	 
				<li>2  - Le champ apparait dans QuickCreate.</li>
			</ul>
	<tr><td>typeofdata</td><td><pre>affichage
voir <a href="https://wiki.vtiger.com/index.php/TypeOfData" target="_blank"> ici </a>
		</pre></td>
</table>