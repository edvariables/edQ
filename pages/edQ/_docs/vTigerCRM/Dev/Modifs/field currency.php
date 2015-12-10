<h2>vTigerCRM - <?=$node['nm']?></h2>
<ul class="edq-doc">

	<li><h3>Champ de type 72 - currency</h3>
		<ul>
			<li>\include\ListView\ListViewController.php
				<pre><code>if($field->getUIType() == 72) {
if($fieldName == 'unit_price') {
	$currencyId = getProductBaseCurrency($recordId,$module);
	$cursym_convrate = getCurrencySymbolandCRate($currencyId);
	$currencySymbol = $cursym_convrate['symbol'];
} else {
	$currencyInfo = getInventoryCurrencyInfo($module, $recordId);
	/*ED140922*/
	if(!$currencyInfo)
		$currencySymbol = "€";/*TODO*/
	else
		$currencySymbol = $currencyInfo['currency_symbol'];
}
</code></pre></li>
			
			
			<li>\include\utils\InventoryUtils.php
				<pre><code>function getInventoryCurrencyInfo($module, $id)
...
$inventory_table = $inv_table_array[$module];
/* ED 140922 */
if(!$inventory_table)
	return false;
</code></pre></li>
		</ul>
	</li>

</ul>