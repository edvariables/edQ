<h2>Affichage de listes dans la page de résumé d'une entité par des widgets</h2>
<pre>
Pour avoir un affichage Detail en Summary :
- nécessite %Module%\DetailViewSummaryContents.tpl
- nécessite %Module%\ModuleSummaryView.tpl


Pour ajouter des widgets pour des tables liées
- nécessite la class %Module%/models/DetailView.php
<code>public function getWidgets() {
	$userPrivilegesModel = Users_Privileges_Model::getCurrentUserPrivilegesModel();
	$widgetLinks = parent::getWidgets();
	$widgets = array();

	$documentsInstance = Vtiger_Module_Model::getInstance('Vehicules');
	if($userPrivilegesModel->hasModuleActionPermission($documentsInstance->getId(), 'DetailView')) {
		$createPermission = $userPrivilegesModel->hasModuleActionPermission($documentsInstance->getId(), 'EditView');
		$widgets[] = array(
				'linktype' => 'DETAILVIEWWIDGET',
				'linklabel' => 'Véhicules',
				'linkName'	=> $documentsInstance->getName(),
				'linkurl' => 'module='.$this->getModuleName().'&view=Detail&record='.$this->getRecord()->getId().
						'&relatedModule=Vehicules&mode=showRelatedRecords&page=1&limit=25',
				'action'	=>	($createPermission == true) ? array('Add') : array(),
				'actionURL' =>	$documentsInstance->getQuickCreateUrl()
		);
	}
	...
</code>


- nécessite un ajout dans modules\Vtiger\views\Detail.php
<code>class Vtiger_Detail_View extends Vtiger_Index_View {
	protected $record = false;

	function __construct() {
		parent::__construct();
		...
		$this->exposeMethod('showRelatedRecords');//ED141025
	}
	</code>


- nécessite l'existence des fichiers Vtiger/%RelatedModule%SummaryWidgetContents.tpl (tout attaché)

		
</pre>