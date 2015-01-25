<h3>Calendrier</h3>
Bug : n'affiche pas les tâches de projet si la date de fin est nulle.
<pre><code>modules\Calendar\actions\Feed.php
protected function pullProjectTasks($start, $end, &amp;$result, $cssClass) {
	$db = PearDatabase::getInstance();
	...
	$query.= <b>" ((startdate &gt;= '$start' AND IFNULL(enddate, startdate) &lt; '$end') 
		OR ( IFNULL(enddate, startdate) &gt;= '$start'))";</b>
</code>

A faire dans toutes les functions pull%Module%

Déplacée dans _docs/vTigerCRM/Dev/Calendar/Manque des elements
</pre>