<?php 
/* Heures travaillées par contexte
Les paramètres de filtres commencent par f-- suivi du nom du champ
*/

$db = get_db();

/* sélection des timeslots et de leur membre le plus profond */
$sql = "SELECT ts.*, tsm.id AS member_id, tsm.name AS member_name
FROM `fo_timeslots` `ts`
JOIN `fo_objects` `tso`
	ON `tso`.`id` = ts.`object_id`
JOIN (
	/* profondeur maxi des membres de l'objet timeslot */
	SELECT tsom.object_id, MAX(mo.depth) AS depth
	FROM `fo_object_members` `tsom` /* membres des timeslot */
	JOIN `fo_members` `mo` /* membres auxquels les timeslot sont rattachés */
		ON `tsom`.`member_id` = `mo`.`id`
	GROUP BY tsom.object_id
) `tsomax`
	ON `tso`.`id` = tsomax.`object_id`
	AND tso.object_type_id = 17
JOIN `fo_object_members` `tsom`
	ON `tsom`.`object_id` = `tsomax`.`object_id`
JOIN `fo_members` `tsm`
	ON `tsom`.`member_id` = tsm.`id`
	AND tsomax.depth = tsm.depth
	AND tsm.dimension_id = 1 /* workspace */
/*JOIN `fo_objects` `tsmo`
	ON `tsmo`.`id` = tsm.`object_id`
	AND tsmo.object_type_id = 1*/
";

/*	$rows = $db->all( $sql . ' LIMIT 0, 200' );

	$args = array('rows' => $rows);
echo count($rows);
	page::call('/_html/table/rows', $args);*/


$sql = "SELECT * FROM (

	SELECT `ou`.object_id AS IdSalarie
	, DATE_FORMAT((`ts`.`start_time`),'%Y-%m') AS `Mois`
	, `ou`.`first_name` AS `Salarié`
	, SUM(TIMESTAMPDIFF(SECOND, `ts`.`start_time`, `ts`.`end_time`))/3600 AS `Heures`
	, SUM(TIMESTAMPDIFF(SECOND, `ts`.`start_time`, `ts`.`end_time`))/3600 * CAST(uv.value AS DECIMAL(9,3)) AS `Valorisation`
	, pr.name AS Contexte
	, IF(pv.value IS NULL OR pv.value = '', 'ERR', pv.value) AS CodeAnalytique
	FROM (" . $sql . ") `ts`
	JOIN `fo_contacts` `ou`
		ON `ou`.`object_id` = `ts`.`contact_id`
	JOIN `fo_members` `pr`
		ON `ts`.`member_id` = `pr`.`id`
	LEFT JOIN fo_member_custom_property_values pv
		ON pv.member_id = pr.id
		AND pv.custom_property_id = ?
	LEFT JOIN fo_object_properties uv
		ON uv.rel_object_id = ou.object_id
		AND uv.name = ?
		
	WHERE (`ts`.`start_time` BETWEEN ? AND ?)
	AND (`ts`.`end_time` <> 0)
	
	GROUP BY `ou`.object_id, date_format((`ts`.`start_time` + interval 1 hour),'%Y-%m')
		, `ou`.`first_name`
		, pr.name, pv.value
	ORDER BY Mois DESC, `Salarié`, CodeAnalytique, Contexte
) a";

	$arguments = array_merge($_REQUEST, isset($arguments) ? $arguments : array());
	
	$where = 0;
	$params = array();

	$custom_property_id = 1; //Id de la propriété CodeAnalytique ajoutée
	$params[] = $custom_property_id;

	$custom_property_id = 'Taux horaire'; //Id de la propriété TauxHoraire ajoutée
	$params[] = $custom_property_id;

	/*$root_id = 0; //Id du parent
	$params[] = $root_id;*/

	$dateDebut = isset($arguments['q--date-debut'])
		? $arguments['q--date-debut']
		: '01/09/' . ((int)date('m') > 8 ? date('Y') : (int)date('Y') - 1);
	$dateTimeDebut = is_string($dateDebut) ? DateTime::createFromFormat('d/m/Y H:i:s', $dateDebut. '00:00:00') : $dateDebut;
	$params[] = $dateTimeDebut->format('Y-m-d H:i:s');

	$dateFin = isset($arguments['q--date-fin'])
		? $arguments['q--date-fin']
		: '31/08/' . ((int)date('m') <= 8 ? date('Y') : (int)date('Y') + 1);
	$dateTimeFin = is_string($dateFin) ? DateTime::createFromFormat('d/m/Y H:i:s', $dateFin . ' 23:59:59') : $dateFin;
	$params[] = $dateTimeFin->format('Y-m-d H:i:s') ;
	
	$maxRows = isset($arguments['q--limit'])
			? ((int)$arguments['q--limit'])
			: 200;
	
	$params[] = 0;
	$params[] = $maxRows;
	//var_dump($params);
	//var_dump($sql);
	
echo ('<pre>' . $sql . '</pre>');
var_dump($params);

	$rows = $db->all( $sql . ' LIMIT ?, ?', $params );

	$args = array('rows' => $rows);
echo count($rows);
	page::call('/_html/table/rows', $args);
	
?>