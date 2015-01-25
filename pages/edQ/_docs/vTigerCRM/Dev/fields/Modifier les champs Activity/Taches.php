<h2>Comment éditer les champs des Activité/Taches de l'Agenda ?</h2>
<ul class="edq-doc">
	<li>On ne peut pas éditer ces champs dans l'éditeur d'agencement.</li>
	<li>Ainsi, il faut travailler avec PhpMyAdmin pour modifier directement la base de données : </li>
		<li> Cela se passe dans la table vtiger_field, les champs des Tâches sont ceux avec tab_id=9,
			les champs des Activités sont ceux avec tab_id = 16</li>
		<h3>Paramètres de la table vtiger_field :</h3>
		<h4> valeurs de la colonne  display type : </h4>
			<ul><li>1 - Champ présent à la fois dans Detail view et Edit view </li>
    	 	<li>2  - Champ présent seulement dans Detail view (Ex. Created time, Modified time) </li>   	 
			<li>3  - Champ absent de Detail view et Edit view, mais qui peut apparaitre dans List view. Le champ n'est alors plus rattaché à un bloc</li>
          <li>4 - Champ de mot de passe </li>	
		</ul>
	<h4> valeurs de la colonne quickcreate : </h4>
			<ul><li>0 - Le champ est obligatoire dans QuickCreate</li>
    	 		<li>1 - Le champ n'apparait pas dans QuickCreate</li>   	 
				<li>2  - Le champ apparait dans QuickCreate.</li>
			</ul>
	<h4> valeurs de la colonne typeofdata : </h4>
	<ul><li>voir <a href="https://wiki.vtiger.com/index.php/TypeOfData" target="_blank"> ici </a></li>
			</ul>