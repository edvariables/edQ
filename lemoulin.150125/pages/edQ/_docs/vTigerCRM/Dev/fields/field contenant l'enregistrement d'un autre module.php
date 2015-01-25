<h2>Comment créer un champ permettant de sélectionner un enregistrement d'un autre module ?</h2>
<ul class="edq-doc">
	<li>1 : avec le gestionnaire d'agencement, créer le champ en indiquant n'importe quel type (par exemple Text) </li>
	<li>Ensuite, il faut travailler avec PhpMyAdmin pour modifier directement la base de données : </li>
		<li> 2 : dans la table vtiger_field, changer le "uitype" du champ créé pour avoir uitype = 10  </li>
	
		<li> 3 : dans la table vtiger_fieldmodulerel, insérer une nouvelle ligne qui décrit la relation avec 
			fieldid=IdDuChampCréé, module = NomDuModuleDuChampCréé, relmodule=NomDuModuleDansLequelOnVeutChoisirLaValeur, status=NULL sequence=NULL. </li>
	</li>	
</ul>
<ul class="edq-doc">
	<li>Lorsque l'on cliquera sur ce champ, une fenêtre Popup de selection permettra de choisir le record voulu. </li>
	
	<h3> Pour éditer les noms de colonnes de cette fenêtre Popup (ou pour les créer s'ils n'existent pas ) : </h3>
	<pre>	 Il faut que soient définis dans la classe MonModuleLié qui se trouve dans le fichier modules/MonModuleLié/MonModuleLié.php
	les tableaux $search_fields et $search_fields_name qui établissent les colonnes de la vue popup :
	</pre>
<pre> Par exemple, ces tableaux ne sont pas présents dans la class Activity du module Calendar (fichier modules/Calendar/Activity.php) :
Il faut donc les créer : 
<code>	
var $search_fields = array (
        'LBL_SUBJECT' => array('calendar','subject'),
        'LBL_ASSIGNED_USER_ID' => array('calendar','assigned_user_id'),
        'LBL_DATE_START' => array('calendar','date_start'),
        'LBL_STATUS' => array('calendar', 'taskstatus'),
        'LBL_ACTIVITYTYPE' => array('calendar', 'activitytype'),
);
    var $search_fields_name = array (
        'LBL_SUBJECT' => 'subject',
        'LBL_ASSIGNED_USER_ID' => 'assigned_user_id',
        'LBL_DATE_START' => 'date_start',
        'LBL_STATUS' => 'taskstatus',
        'LBL_ACTIVITYTYPE' => 'activitytype',       
);
</code>
</pre>
	
</ul>