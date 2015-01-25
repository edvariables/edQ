{*<!--
/*********************************************************************************
  ** The contents of this file are subject to the vtiger CRM Public License Version 1.0
   * ("License"); You may not use this file except in compliance with the License
   * The Original Code is:  vtiger CRM Open Source
   * The Initial Developer of the Original Code is vtiger.
   * Portions created by vtiger are Copyright (C) vtiger.
   * All Rights Reserved.
  *
 ********************************************************************************/
 /* ED141009
  * copy of \layouts\vlayout\modules\Vtiger\uitypes\String.tpl
 */
-->*}
{strip}
{assign var="FIELD_INFO" value=Vtiger_Util_Helper::toSafeHTML(Zend_Json::encode($FIELD_MODEL->getFieldInfo()))}
{assign var="SPECIAL_VALIDATOR" value=$FIELD_MODEL->getValidator()}
{assign var="FIELD_NAME" value=$FIELD_MODEL->get('name')}
{assign var="VALUE" value=$FIELD_MODEL->get('fieldvalue')}
{assign var="INPUT_ID" value="`$MODULE`_editView_fieldName_`$FIELD_NAME`"} {*{$MODULE}_editView_fieldName_{$FIELD_NAME}*}
<input id="{$INPUT_ID}" type="hidden" 
	class="input-large {if $FIELD_MODEL->isNameField()}nameField{/if}" 
	data-validation-engine="validate[{if $FIELD_MODEL->isMandatory() eq true}required,{/if}funcCall[Vtiger_Base_Validator_Js.invokeValidation]]" 
	name="{$FIELD_MODEL->getFieldName()}" 
	value="{$VALUE}"
	{if $FIELD_MODEL->isReadOnly()} 
		readonly 
	{/if} 
data-fieldinfo='{$FIELD_INFO}' {if !empty($SPECIAL_VALIDATOR)}data-validator={Zend_Json::encode($SPECIAL_VALIDATOR)}{/if} />
<div id="{$INPUT_ID}-colorSelector" class="colorpicker-holder"><div style="background-color: {$VALUE}"></div></div>
{if !$FIELD_MODEL->isReadOnly()}
<script>$().ready(function(){
	$('#{$INPUT_ID}-colorSelector').ColorPicker({
		color: '{$VALUE}',
		onShow: function (colpkr) {
			$(colpkr).fadeIn(200);
			return false;
		},
		onHide: function (colpkr) {
			var $input = $('#{$INPUT_ID}');
			if ($input.parent().hasClass('edit')) { /*Detail view -> Edit on click*/
				$input
					.parent().prev().click() /* ne valide pas mais permet de declencher le submit en 1 seul clic ailleurs. Enfin, c'est bizarre
											  * TODO : 	gerer le reset a la couleur d'origine (clic en haut a droite du pickcolor)
											  *			apres enregistrement, affiche le #010fE24 de la couleur et le pickcolor ne fonctionne plus
											  * 		*/
				;
			}
			$(colpkr).fadeOut(200);
			return false;
		},
		onChange: function (hsb, hex, rgb) {
			$('#{$INPUT_ID}').val('#' + hex);
			$('#{$INPUT_ID}-colorSelector div').css('backgroundColor', '#' + hex);
		}
	});
});</script>
{/if}
{/strip}