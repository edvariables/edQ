<h3>\modules\Vtiger\models\Record.php</h3>
<pre><code>
	/**
	 * Function to retieve display value for a field
	 * @param &lt;String&gt; $fieldName - field name for which values need to get
	 * @param &lt;Integer&gt; $recordId - record
	 <b>* @param &lt;Boolean&gt; if field is unknown, returns the value otherwise FALSE value ED140907
	 *	in .tpl : {$RELATED_RECORD->getDisplayValue($RELATED_HEADERNAME, false, true)}</b>
	 * @return &lt;String&gt;
	 */
	public function getDisplayValue($fieldName, $recordId = false, $unknown_field_returns_value = false) {
		if(empty($recordId)) {
			$recordId = $this->getId();
		}

		$fieldModel = $this->getModule()->getField($fieldName);
		if($fieldModel) {
			return $fieldModel->getDisplayValue($this->get($fieldName), $recordId, $this);
		}
		<b>if($unknown_field_returns_value)
			return $this->get($fieldName);</b>
		return false;
	}
</code></pre>