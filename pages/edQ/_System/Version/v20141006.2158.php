< ?php
	  $db = get_db('/_System/dataSource');
$db->query('UPDATE `tree_data` SET `ulvl` = 256');


var_dump($db->all('SELECT `ulvl` FROM  `tree_data`'));
? >

< ?php
$db = get_db('/_System/dataSource');
$db->query("INSERT INTO `parameter` (`Domain`, `IdParam`, `Label`, `Value`, `ValueType`, `Image`, `Description`, `Data`, `SortIndex`, `IsSystem`) VALUES
('USER.TYPE', '1024', 'Invité', NULL, NULL, NULL, NULL, NULL, 0, 0),
('USER.TYPE', '256', 'Interne', NULL, NULL, NULL, NULL, NULL, 0, 0)");


//var_dump($db->all('SELECT `ulvl` FROM  `tree_data`'));
? >