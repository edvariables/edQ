<h2>vTigerCRM - <?=$node['nm']?></h2>
<ul class="edq-doc">
	
	<li><h3><code>layouts\vlayout\modules\Calendar\resources</code></h3>
		<ul><li><h4>ligne 261 : hauteur du calendrier</h4>
				<pre><code>height: 800,</code></pre></li>
			<li><h4>ligne 295 : time formats</h4>
				<pre>Corriger les formats de dates
<code>	titleFormat: {
		month: 'MMMM yyyy',
		week: "d MMM[ yyyy]{ '&amp;#8212;'d [ MMM] yyyy}",
		day: 'dddd, d MMM, yyyy'
	},
	columnFormat: {
		month: 'ddd',
		week: 'ddd d/M',
		day: 'dddd d/M'
	},
	
	timeFormat: {
		agenda: 'H:mm{ - H:mm}'
	}</code></pre>
			</li>
		</ul>
	</li>
	
	
	<li><h3><code>modules\Import\helpers\Utils.php</code></h3>
		<ul><li><h4>ligne 49 : contrôle de l'existence du répertoire d'import car il peut être purgé avec le cache</h4>
				<pre><code>/* ED140824
*/ if($import_dir && !file_exists($import_dir))
mkdir($import_dir);</code></pre></li>
		</ul>
	</li>
	
	<li><h3><code>modules\Import\actions\Data.php</code></h3>
		<ul><li><h4>ligne 217 : Erreur SQL, manque un AND</h4>
				<pre><code>/* ED140827
* Erreur SQL, manque un AND car déjà initialisé avec un premier filtre (import de contacts)
* Les imports réutilisent le filtre en-cours.
*/
$counter = $queryGenerator->conditionInstanceCount;

$mergeFields = $this->mergeFields;
foreach ($mergeFields as $index => $mergeField) {
	/* ED140827 */
	if ($counter++ != 0) {
		$queryGenerator->addConditionGlue(QueryGenerator::$AND);
	}
	</code></pre></li>
</ul>