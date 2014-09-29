<h2>Comment créer une <var>picklist</var> ?</h2>
<ul class="edq-doc">

	<li>Il s'agit simplement de créer une balise<code>&lt;select class="select2"; multiple=""/;&gt;</code> 
	<li><h5>Exemple</h5>
	<li>Fichier <var>\layouts\vlayout\modules\MonModule\EditViewBlocks.tpl</var>
		<pre>Ajouter ceci:
		<code>&lt;table class="table table-bordered blockContainer showInlineTable"&gt;
        &lt;tr&gt;
            &lt;th class="blockHeader" colspan="4"&gt;A bloc !&lt;/th&gt;
        &lt;/tr&gt;
        &lt;tr&gt;
            &lt;td class="fieldLabel {$WIDTHTYPE}"&gt;&lt;label class="muted pull-right marginRight10px"&gt;A bloc too&lt;/label&gt;&lt;/td&gt;
            &lt;td class="fieldValue {$WIDTHTYPE}"&gt;
                 &lt;select id="selectedRel1" class="select2" multiple="" name="selected_rel1[]" style="width: 200px; display: none;"&gt;
		    &lt;option value="5" selected=""&gt;Mon premier&lt;/option&gt;
		    &lt;option value="6" &gt;Mon second&lt;/option&gt;
		    &lt;option value="17" selected=""&gt;Mon troisi&amp;eacute;me&lt;/option&gt;
		 &lt;/select&gt;
            &lt;/td&gt;
        &lt;/tr&gt;
   &lt;/table&gt;

     &lt;table class="table table-bordered blockContainer showInlineTable"&gt;
        &lt;tr&gt;
            &lt;th class="blockHeader" colspan="4"&gt;A bloc 2 !&lt;/th&gt;
        &lt;/tr&gt;
        &lt;tr&gt;
            &lt;td class="fieldLabel {$WIDTHTYPE}"&gt;&lt;label class="muted pull-right marginRight10px"&gt;A bloc too&lt;/label&gt;&lt;/td&gt;
            &lt;td class="fieldValue {$WIDTHTYPE}"&gt;
                 &lt;select id="selectedRel2" class="select2" multiple="" name="selected_rel2[]" style="width: 200px; display: none;"&gt;
		    {foreach key=ID item=NAME from=$TOUS_LES_REL2}
                    &lt;option value="{$ID}" {if in_array($ID, $MES_REL2)}selected{/if}&gt;
			{$NAME}
		    &lt;/option&gt;
                {/foreach}
		 &lt;/select&gt;
            &lt;/td&gt;
        &lt;/tr&gt;
   &lt;/table&gt;</code>
		</pre></li>
	
	<li>Fichier <var>\layouts\vlayout\modules\MonModule\EditViewBlocks.tpl</var>
		<pre><code>
Class MonModule_Edit_View extends Vtiger_Edit_View {

	function __construct() {
		parent::__construct();
		$this-&gt;exposeMethod('Rel2');
	}
	
	function Rel2($request, $moduleName) {
		$currentUser = Users_Record_Model::getCurrentUserModel();
        
		$viewer = $this-&gt;getViewer ($request);
		$record = $request-&gt;get('record');

 		if(!empty($record) &amp;&amp; $request-&gt;get('isDuplicate') == true) {
			$recordModel = Vtiger_Record_Model::getInstanceById($record, $moduleName);
			$viewer-&gt;assign('MODE', '');
		}else if(!empty($record)) {
			$recordModel = Vtiger_Record_Model::getInstanceById($record, $moduleName);
			$viewer-&gt;assign('MODE', 'edit');
			$viewer-&gt;assign('RECORD_ID', $record);
		} else {
			$recordModel = Vtiger_Record_Model::getCleanInstance($moduleName);
			$viewer-&gt;assign('MODE', '');
		}
		
		$eventModule = Vtiger_Module_Model::getInstance($moduleName);
		$recordModel->setModuleFromInstance($eventModule);
		
		$tous_les_rel2 = array(
			"1" =&gt; "Mon premier",
			"2" =&gt; "Mon second",
			"3" =&gt; "Mon troisieme",
			"4" =&gt; "Mon quatrieme",
		);
		$viewer-&gt;assign('TOUS_LES_REL2', $tous_les_rel2);
	
		//$mes_rel2 = $recordModel->get_mes_rel2(); // function get_mes_rel2() à créer dans <var>modules/MonModule/model/Record.php</var>
		$mes_rel2 = array(
			"2",
			"3",
		);
		$viewer-&gt;assign('MES_REL2', $mes_rel2);

		$viewer-&gt;view('EditView.tpl', $moduleName);
	}
}
	</code>
		</pre></li>
	
</ul>