-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Client :  127.0.0.1
-- Généré le :  Mar 02 Septembre 2014 à 08:43
-- Version du serveur :  5.6.17
-- Version de PHP :  5.5.12

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données :  `edq`
--

-- --------------------------------------------------------

--
-- Structure de la table `contact`
--

DROP TABLE IF EXISTS `contact`;
CREATE TABLE IF NOT EXISTS `contact` (
  `IdContact` int(11) NOT NULL AUTO_INCREMENT,
  `IdContactRef` int(11) NOT NULL,
  `ContactType` varchar(8) NOT NULL,
  `Name` varchar(64) NOT NULL,
  `ShortName` varchar(16) DEFAULT NULL,
  `Title` varchar(16) DEFAULT NULL,
  `EMail` varchar(128) NOT NULL,
  `Phone1` varchar(23) DEFAULT NULL,
  `Phone2` varchar(23) DEFAULT NULL,
  `Address` text,
  `ZipCode` varchar(8) DEFAULT NULL,
  `City` varchar(64) DEFAULT NULL,
  `Enabled` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`IdContact`),
  KEY `Enabled` (`Enabled`),
  KEY `IdContactRef` (`IdContactRef`),
  KEY `EMail` (`EMail`),
  KEY `Name` (`Name`),
  KEY `ContactType` (`ContactType`),
  KEY `Title` (`Title`),
  KEY `ShortName` (`ShortName`),
  KEY `ZipCode` (`ZipCode`),
  KEY `City` (`City`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=766 ;

--
-- Contenu de la table `contact`
--

INSERT INTO `contact` (`IdContact`, `IdContactRef`, `ContactType`, `Name`, `ShortName`, `Title`, `EMail`, `Phone1`, `Phone2`, `Address`, `ZipCode`, `City`, `Enabled`) VALUES
(-1, 0, 'ENT', 'VTS', 'VTS', '', '', '', '', '', '', '', 1),
(1, 0, '', 'Administrateur', 'sys', '', '', '', '', '', '', '', 1),
(715, 0, '', 'Opérateur', 'OPER', '', '', '', '', '', '', '', 1),
(720, 0, '', 'Olivier DUFOSSE', 'OD', 'M', '', '', NULL, NULL, NULL, NULL, 1),
(722, 0, '', 'Georges BRICHON', 'GB', 'M', '', '', NULL, NULL, NULL, NULL, 1),
(742, 0, '', 'Christian FEVRE', 'CF', 'M.', '', '', NULL, NULL, NULL, NULL, 1),
(744, 0, '', 'Prissé', '', '', '', '', '', '', '', '', 1),
(745, 0, '', 'Sologny', NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, 1),
(747, 0, '', 'Verzé', NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, 1),
(757, 0, 'DOM', 'Externes', '', '', '', '', '', '', '', '', 1),
(758, 0, 'ENT', 'DIVERS', NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, 1),
(759, 0, 'DOM', 'Transporteurs', '', '', '', '', '', '', '', '', 1),
(761, 0, 'DOM', 'Assemblages', '', '', '', '', '', '', '', '', 1),
(762, 0, '', 'Vincent BIGEARD', 'VB', NULL, '', '', NULL, NULL, NULL, NULL, 1),
(763, 0, '', 'Sylvain JOLIVET', 'SJ', NULL, '', '', NULL, NULL, NULL, NULL, 1),
(764, 0, '', 'Rémi MOLLE', 'RM', NULL, '', '', NULL, NULL, NULL, NULL, 1),
(765, 0, '', 'Gilles CORTAMBERT', 'GC', NULL, '', '', NULL, NULL, NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Structure de la table `contactparam`
--

DROP TABLE IF EXISTS `contactparam`;
CREATE TABLE IF NOT EXISTS `contactparam` (
  `IdContact` int(11) NOT NULL,
  `Domain` varchar(16) NOT NULL,
  `IdParam` varchar(16) NOT NULL,
  `Index` tinyint(4) NOT NULL DEFAULT '0',
  `Data` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`IdContact`,`Domain`,`IdParam`,`Index`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `localparameter`
--

DROP TABLE IF EXISTS `localparameter`;
CREATE TABLE IF NOT EXISTS `localparameter` (
  `Domain` varchar(16) NOT NULL,
  `IdParam` varchar(16) NOT NULL,
  `Label` varchar(128) DEFAULT NULL,
  `Value` varchar(128) DEFAULT NULL,
  `ValueType` varchar(16) DEFAULT NULL,
  `Description` varchar(1024) DEFAULT NULL,
  `Data` text,
  `SortIndex` int(11) NOT NULL DEFAULT '1',
  `IsSystem` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`Domain`,`IdParam`),
  KEY `SortIndex` (`SortIndex`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `localparameter`
--

INSERT INTO `localparameter` (`Domain`, `IdParam`, `Label`, `Value`, `ValueType`, `Description`, `Data`, `SortIndex`, `IsSystem`) VALUES
('{SYS}', 'DBVERSION', NULL, 'v01-000-003-001', NULL, NULL, NULL, 1, 0);

-- --------------------------------------------------------

--
-- Structure de la table `node_comment`
--

DROP TABLE IF EXISTS `node_comment`;
CREATE TABLE IF NOT EXISTS `node_comment` (
  `id` int(10) unsigned NOT NULL,
  `value` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `node_comment`
--

INSERT INTO `node_comment` (`id`, `value`) VALUES
(1069, 'Application TerraFact'),
(1168, 'https://datatables.net/examples/'),
(1232, 'Lien de téléchargement du fichier .csv d''une table fournie par le noeud actuel :\n		< ?php /* télécharger */\n		$viewer = tree::get_node_by_name(''/_Exemples/Convertisseurs/table/csv'')[''id'']; /* === 1233 */\n		$viewer_options = "&node=" . $node[''id''] //fournisseur de données\n				. "&file--name=" . urlencode($node[''nm''])\n				. "&node--get=html"; // extrait les données depuis le html\n		? ><a class="file-download" href="view.php?id=<?= $viewer ?><?= $viewer_options ?>&vw=file.call">télécharger</a>\n	'),
(1251, 'Utilisation de la fonction page::view_url.\nFournit une url d''appel d''une page.\n< form action="<?= page::view_url( $node ) ?>" >...\n\njQuery.load\njQuery.ui.dialog\n'),
(1319, 'La page zip peut s''appeler directement par :\n< ?php\n$arguments[''backup''] = ''sql pages sources'';\ncall_page( ''/_System/Sauvegarde/zip'', $arguments);\n? >'),
(1320, '\n\nMYSQLDUMP doit être défini dans /conf/edQ.conf.php'),
(1341, 'exemple de manipulation Xml'),
(1350, 'retourne le code html des données fournies par l''arguments rows'),
(1361, 'Retourne le code html des données fournies par l''arguments rows.\nUtilise le plugin jquery dataTable\nL''argument columns est transmis au plugin.\nLa page html/table/rows appelle cette page si l''argument $--plugin.'),
(1378, 'Recherche dans les sources .php, .css, .js'),
(1389, '\nModification de jquery.dataTables.js\n140811 - _fnInvalidateRow : makes function can return jquery object'),
(1391, 'ED 140811 : Modification du plugin dataTable pour que la propriété render puisse être une fonction qui retourne du jQuery');

-- --------------------------------------------------------

--
-- Structure de la table `node_param`
--

DROP TABLE IF EXISTS `node_param`;
CREATE TABLE IF NOT EXISTS `node_param` (
  `id` int(10) unsigned NOT NULL,
  `param` varchar(32) NOT NULL,
  `domain` varchar(32) NOT NULL,
  `value` text,
  `sortIndex` int(11) NOT NULL DEFAULT '999',
  PRIMARY KEY (`id`,`param`,`domain`),
  KEY `sortIndex` (`sortIndex`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `node_param`
--

INSERT INTO `node_param` (`id`, `param`, `domain`, `value`, `sortIndex`) VALUES
(1100, 'sort', 'viewers', '["file-call","file-content","","node","comment","children"]', 999),
(1101, 'sort', 'viewers', '["file-call","","node","comment","file-content"]', 999),
(1111, 'sort', 'viewers', '["file-content","","node","comment","file-call"]', 999),
(1123, 'Caption', 'query', '''Noeud : '' . $node->name', 999),
(1123, 'Columns', 'query', '"IdContact" => array(\n	 "text" => "#"\n	 , "pk" => true\n	 , "attributes" => array(''style'' => "background-color: #FAFAFA")\n)\n, "Name" => array(\n	 "text" => "Nom"\n	 , "css" => "background-color: yellow"\n)\n, "EMail" => "EMail"\n, "Phone1" => array(\n	 "text" => function(){ return "Téléphone(s)"; }\n	 , "value" => function($row, $column){\n	 	 return $row["Phone1"] . ($row["Phone2"] == '''' ? '''' : '' - '' . $row["Phone2"]);\n	 })\n, "Phone2" => array("visible" => false)\n, "Address" => array("text" => "Adresse")\n, "ZipCode" => array("text" => "Code postal")\n, "City" => array("text" => "Ville")', 999),
(1123, 'Foot', 'query', 'function($node, $rows, $viewer){\n	 return ''<center>'' . count($rows) . '' ligne'' . (count($rows) > 1 ? ''s'' : '''') . ''</center>'';\n}', 999),
(1123, 'SQLDelete', 'query', 'DELETE FROM contact\nWHERE a.id = :ID', 999),
(1123, 'SQLInsert', 'query', 'INSERT INTO contact (id, name)\nVALUES(:ID, :NAME)', 999),
(1123, 'SQLSelect', 'query', 'SELECT IdContact, Name, `EMail`, `Phone1`, `Phone2`, `Address`, `ZipCode`, `City`\nFROM contact c\nWHERE c.Name <> ''''\nORDER BY c.Name\nLIMIT 0, 20', 999),
(1123, 'SQLUpdate', 'query', 'UPDATE contact\n	SET a.name = :NAME\nWHERE a.id = :ID', 999),
(1134, 'Columns', 'query', '"idContact" => "#",\n"name" => "Nom"', 999),
(1134, 'SQLSelect', 'query', 'SELECT a.idContact, a.name\nFROM contact a\nORDER BY name\nLIMIT 0, 55\n', 999),
(1157, 'Caption', 'query', '''Noeud : '' . $node->name', 999),
(1157, 'Columns', 'query', '"IdContact" => array(\n	 "text" => "#"\n	 , "pk" => true\n)\n, "Name" => array("text" => "Nom")\n, "EMail" => "EMail"\n, "Phone1" => array(\n	 "text" => function(){ return "Téléphone(s)"; }\n	 , "value" => function($row, $column){\n	 	 return $row["Phone1"] . ($row["Phone2"] == '''' ? '''' : '' - '' . $row["Phone2"]);\n	 })\n, "Phone2" => array("visible" => false)\n, "Address" => array("text" => "Adresse")\n, "ZipCode" => array("text" => "Code postal")\n, "City" => array("text" => "Ville")', 999),
(1157, 'Foot', 'query', 'function($node, $rows, $viewer){\n	 return ''<center>'' . count($rows) . '' ligne'' . (count($rows) > 1 ? ''s'' : '''') . ''</center>'';\n}', 999),
(1157, 'SQLDelete', 'query', 'DELETE FROM contact\nWHERE a.id = :ID', 999),
(1157, 'SQLInsert', 'query', 'INSERT INTO contact (id, name)\nVALUES(:ID, :NAME)', 999),
(1157, 'SQLSelect', 'query', 'SELECT IdContact, Name, `EMail`, `Phone1`, `Phone2`, `Address`, `ZipCode`, `City`\nFROM contact c\nWHERE c.Name <> ''''\nORDER BY c.Name\nLIMIT 0, 30', 999),
(1157, 'SQLUpdate', 'query', 'UPDATE contact\n	SET a.name = :NAME\nWHERE a.id = :ID', 999),
(1162, 'Caption', 'query', '''Noeud : '' . $node->name', 999),
(1162, 'Columns', 'query', '"domain" => array(\n	 "text" => "Domaine"\n	 , "css" => "background-color: #FAFAFA;"\n)\n, "param" => array(\n	 "text" => "Paramètre"\n	 , "css" => "background-color: #FAFAFA;"\n)\n, "text" => "Nom"\n, "valueType" => "Type"\n, "icon" => "Image"\n, "defaultValue" => "Valeur par défaut"\n, "comment" => "Commentaire"\n, "sortIndex" => "Tri"', 999),
(1162, 'Foot', 'query', 'function($node, $rows, $viewer){\n	 return ''<center>'' . count($rows) . '' ligne'' . (count($rows) > 1 ? ''s'' : '''') . ''</center>'';\n}', 999),
(1162, 'SQLDelete', 'query', 'DELETE FROM contact\nWHERE a.id = :ID', 999),
(1162, 'SQLInsert', 'query', 'INSERT INTO contact (id, name)\nVALUES(:ID, :NAME)', 999),
(1162, 'SQLSelect', 'query', 'SELECT p.`domain`, p.`param`, p.`text`, p.`valueType`, p.`icon`, p.`defaultValue`, p.`comment`, p.`sortIndex`\n, COUNT(n.id) AS nbUse\nFROM \n	node_params p\nLEFT JOIN\n	node_param n\n	ON p.domain = n.domain\n	AND p.param = n.param\nGROUP BY\n	p.`domain`, p.`param`, p.`text`, p.`valueType`, p.`icon`, p.`defaultValue`, p.`comment`, p.`sortIndex`\nORDER BY\n	 p.`domain`, p.`sortIndex`, p.`text`, p.`param`\nLIMIT 20', 999),
(1162, 'SQLUpdate', 'query', 'UPDATE node_params p\nSET p.text = :TEXT\nWHERE p.domain = :DOMAIN\nAND p.param = :PARAM', 999),
(1196, 'sort', 'viewers', '["file-content","","node","comment","file-call"]', 999),
(1202, 'sort', 'viewers', '["file-call","","node","comment","children","file-content"]', 999),
(1222, 'sort', 'viewers', '["file-content","file-call","","node","comment","children"]', 999),
(1230, 'sort', 'viewers', '["file-call","","node","comment","file-content"]', 999),
(1231, 'sort', 'viewers', '["file-content","file-call","","node","comment"]', 999),
(1234, 'sort', 'viewers', '["file-content","file-call","","node","comment"]', 999),
(1241, 'sort', 'viewers', '["file-call","","node","comment","children","file-content"]', 999),
(1248, 'sort', 'viewers', '["file-call","","node","comment","children","file-content"]', 999),
(1250, 'sort', 'viewers', '["file-content","","node","comment","file-call"]', 999),
(1319, 'sort', 'viewers', '["file-call","","node","comment","children","file-content"]', 999),
(1320, 'sort', 'viewers', '["file-call","","node","comment","file-content"]', 999),
(1322, 'sort', 'viewers', '["file-call","file-content","","node","comment","children"]', 999),
(1326, 'sort', 'viewers', '["file-content","file-call","comment","","node","children"]', 999),
(1327, 'sort', 'viewers', '["file-call","file-content","","node","comment","children"]', 999),
(1342, 'sort', 'viewers', '["file-call","file-content","","node","comment","children"]', 999),
(1344, 'sort', 'viewers', '["file-call","file-content","","node","comment"]', 999),
(1350, 'sort', 'viewers', '["file-content","file-call","","comment","node","children"]', 999),
(1354, 'sort', 'viewers', '["","node","comment","children","file-content","file-call"]', 999),
(1356, 'sort', 'viewers', '["file-call","file-content","","node","comment"]', 999),
(1361, 'sort', 'viewers', '["file-call","file-content","","node","comment","children"]', 999),
(1367, 'sort', 'viewers', '["file-call","file-content","","node","comment","children"]', 999),
(1370, 'sort', 'viewers', '["file-call","file-content","children","","node","comment"]', 999),
(1374, 'sort', 'viewers', '["file-call","file-content","","node","comment"]', 999),
(1377, 'sort', 'viewers', '["","node","comment","children","file-content","file-call"]', 999),
(1378, 'sort', 'viewers', '["file-content","file-call","","node","comment"]', 999),
(1383, 'sort', 'viewers', '["file-call","file-content","","node","comment"]', 999),
(1387, 'sort', 'viewers', '["file-call","","node","comment","children","file-content"]', 999),
(1388, 'sort', 'viewers', '["file-call","file-content","","node","comment"]', 999),
(1390, 'sort', 'viewers', '["file-call","file-content","","node","comment"]', 999),
(1392, 'sort', 'viewers', '["file-content","","node","comment","file-call"]', 999),
(1397, 'sort', 'viewers', '["file-content","file-call","","node","comment"]', 999),
(1400, 'sort', 'viewers', '["file-content","file-call","","node","comment"]', 999),
(1401, 'sort', 'viewers', '["file-content","file-call","","node","comment"]', 999),
(1402, 'sort', 'viewers', '["file-content","file-call","","node","comment"]', 999),
(1403, 'sort', 'viewers', '["file-content","file-call","","node","comment"]', 999);

-- --------------------------------------------------------

--
-- Structure de la table `node_params`
--

DROP TABLE IF EXISTS `node_params`;
CREATE TABLE IF NOT EXISTS `node_params` (
  `param` varchar(32) NOT NULL,
  `domain` varchar(32) NOT NULL,
  `text` varchar(64) DEFAULT NULL,
  `valueType` varchar(128) DEFAULT NULL,
  `icon` varchar(64) DEFAULT NULL,
  `defaultValue` text,
  `comment` text,
  `sortIndex` int(11) NOT NULL DEFAULT '999',
  PRIMARY KEY (`param`,`domain`),
  KEY `sortIndex` (`sortIndex`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `node_params`
--

INSERT INTO `node_params` (`param`, `domain`, `text`, `valueType`, `icon`, `defaultValue`, `comment`, `sortIndex`) VALUES
('Caption', 'query', 'Titre', NULL, 'file file-html', 'function($node, $rows, $viewer){\n	 return ''Noeud : '' . $node["nm"];\n}', NULL, 1),
('Columns', 'query', 'Colonnes', NULL, NULL, '"IdContact" => array("text" => "#")\n, "Name" => array("text" => "Nom")\n, "EMail" => "EMail"\n, "Phone1" => array(\n	 "text" => function(){ return "Téléphone(s)"; }\n	 , "value" => function($row, $column){\n	 	 return $row["Phone1"] . ($row["Phone2"] == '''' ? '''' : '' - '' . $row["Phone2"]);\n	 })\n, "Phone2" => array("visible" => false)\n, "Address" => array("text" => "Adresse")\n, "ZipCode" => array("text" => "Code postal")\n, "City" => array("text" => "Ville")', NULL, 3),
('Foot', 'query', 'Pied de table', NULL, NULL, 'function($node, $rows, $viewer){\n	 return ''<center>'' . count($rows) . '' ligne'' . (count($rows) > 1 ? ''s'' : '''') . ''</center>'';\n}', NULL, 5),
('Footer', 'query', 'Pieds de colonnes', NULL, NULL, NULL, NULL, 4),
('SQLDelete', 'query', '', NULL, NULL, 'DELETE FROM contactWHERE a.id = :ID', NULL, 8),
('SQLInsert', 'query', '', NULL, NULL, 'INSERT INTO contact (id, name)VALUES(:ID, :NAME)', NULL, 7),
('SQLSelect', 'query', '', NULL, 'file file-sql', 'SELECT a.id, a.name\nFROM contact a\nORDER BY name\nLIMIT 0, 55', NULL, 2),
('SQLUpdate', 'query', NULL, NULL, NULL, 'UPDATE contact\n	SET a.name = :NAME\nWHERE a.id = :ID', NULL, 6);

-- --------------------------------------------------------

--
-- Structure de la table `parameter`
--

DROP TABLE IF EXISTS `parameter`;
CREATE TABLE IF NOT EXISTS `parameter` (
  `Domain` varchar(16) NOT NULL,
  `IdParam` varchar(16) NOT NULL,
  `Label` varchar(128) DEFAULT NULL,
  `Value` varchar(128) DEFAULT NULL,
  `ValueType` varchar(16) DEFAULT NULL,
  `Image` varchar(32) DEFAULT NULL,
  `Description` varchar(1024) DEFAULT NULL,
  `Data` text,
  `SortIndex` int(11) NOT NULL DEFAULT '1',
  `IsSystem` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`Domain`,`IdParam`),
  KEY `SortIndex` (`SortIndex`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `parameter`
--

INSERT INTO `parameter` (`Domain`, `IdParam`, `Label`, `Value`, `ValueType`, `Image`, `Description`, `Data`, `SortIndex`, `IsSystem`) VALUES
('.', '.', 'Domaines', '', '', NULL, NULL, '', 0, 1),
('.', 'ART', 'Articles', '', '', 'Article', '', '', 9999, 0),
('.', 'CONT', 'Contacts', '', ' ', 'Contact', '', '', 9999, 0),
('.', 'DOSS', 'Dossiers', NULL, NULL, 'Dossiers', NULL, NULL, 9999, 0),
('.', 'IMAGE', 'Images', '', '', 'Image', '', '', 9999, 0),
('.', 'Q', 'Requêtes', NULL, NULL, NULL, NULL, NULL, 1, 0),
('.', 'UNIT', 'Unités de mesure', '', '', 'Selection', '', '', 9999, 0),
('.', 'USER', 'Utilisateurs', NULL, NULL, 'User', NULL, NULL, 9999, 1),
('.', 'VALUETYPE', 'Types de valeurs', 'Selection', ' ', 'Selection', '', NULL, 9999, 0),
('ART', 'ART.CAT', 'Catégories', '', '', NULL, '', '', 9999, 0),
('ART.CAT', 'ART.CAT.HTML', 'Html', 'ANA', '', NULL, '', '', 9999, 0),
('ART.CAT.HTML', 'default', '(par défault)', '<span class="edvicon <?=edv.qv.Params.image(this.Value("Image"))?>"><?=this.Value("Name")?></span>', NULL, NULL, NULL, '', 0, 0),
('CONT', 'CONT.P', 'Paramètres des contacts', NULL, NULL, NULL, NULL, NULL, 9999, 0),
('CONT', 'CONT.TITLE', 'Civilités', NULL, NULL, NULL, NULL, NULL, 9999, 0),
('CONT', 'CONT.TYPE', 'Types de contact', NULL, NULL, NULL, NULL, NULL, 9999, 0),
('CONT.P', 'CONT.P.ETAB', 'Etablissement', NULL, NULL, NULL, NULL, NULL, 9999, 0),
('CONT.P.ETAB', 'Code', 'Code interne', NULL, 'Selection', NULL, NULL, '{"values":[1,2,3,4,5,99]}', 9999, 0),
('CONT.TITLE', '', '', NULL, NULL, NULL, NULL, NULL, 0, 0),
('CONT.TITLE', 'M', 'M.', NULL, NULL, NULL, NULL, NULL, 1, 0),
('CONT.TITLE', 'MME', 'Mme', NULL, NULL, NULL, NULL, NULL, 2, 0),
('CONT.TYPE', '', 'Contact', '', '', 'Contact', '', '', 0, 0),
('CONT.TYPE', 'BOSS', 'Responsable', '', '', NULL, NULL, '', 4, 0),
('CONT.TYPE', 'DOM', 'Domaine', NULL, NULL, NULL, NULL, NULL, 9999, 0),
('CONT.TYPE', 'ENT', 'Entreprise', NULL, NULL, NULL, NULL, NULL, 9999, 0),
('CONT.TYPE', 'ETAB', 'Etablissement', NULL, NULL, NULL, NULL, '', 9999, 0),
('DOSS', 'DOSS.P', 'Paramètres', NULL, NULL, NULL, NULL, NULL, 9999, 0),
('DOSS', 'DOSS.STA', 'Statuts des dossiers', NULL, NULL, NULL, NULL, '', 6, 0),
('DOSS', 'DOSS.TYPE', 'Types', '', '', NULL, NULL, '', 5, 0),
('DOSS.P', 'COM', 'Commentaire interne', NULL, 'Text', NULL, NULL, NULL, 99999, 0),
('DOSS.STA', 'ANNUL', 'Annulé', NULL, NULL, NULL, NULL, NULL, 4, 0),
('DOSS.STA', 'CLOS', 'Terminé', NULL, NULL, NULL, NULL, NULL, 10, 0),
('DOSS.STA', 'NEW', 'Nouveau', NULL, NULL, NULL, NULL, NULL, 0, 0),
('DOSS.STA', 'OK', 'Enregistré', NULL, NULL, NULL, NULL, NULL, 1, 0),
('DOSS.TYPE', 'FACT', 'Facture', '', '', 'Dossier', '', '', 0, 0),
('IMAGE', 'Array', 'Tableau', 'imgEDVTypeArray', '', 'Array', '', '', 3, 0),
('IMAGE', 'Article', 'Article', 'edvimgArticle', NULL, 'Article', NULL, NULL, 300, 0),
('IMAGE', 'Articles', 'Articles', 'edvimgArticles', NULL, 'Articles', NULL, NULL, 301, 0),
('IMAGE', 'ArtIN', 'Vers conteneur', 'edvimgArtIN', NULL, 'ArtIN', NULL, NULL, 1000, 0),
('IMAGE', 'ArtOUT', 'Depuis conteneur', 'edvimgArtOUT', NULL, 'ArtOUT', NULL, NULL, 1100, 0),
('IMAGE', 'Boolean', 'Oui/Non', NULL, NULL, 'Boolean', NULL, NULL, 9900, 0),
('IMAGE', 'Button', 'Action', NULL, NULL, 'Button', NULL, NULL, 9999, 0),
('IMAGE', 'CatTRANS', 'Transferts', 'edvimgCatTRANS', NULL, 'CatTRANS', NULL, NULL, 2000, 0),
('IMAGE', 'Contact', 'Contact', 'edvimgContact', NULL, 'Contact', NULL, NULL, 9999, 0),
('IMAGE', 'Cuve', 'Cuve', 'edvimgCuve', NULL, 'Cuve', NULL, NULL, 100, 0),
('IMAGE', 'CuveCAM', 'Camion', 'edvimgCuveCAM', NULL, 'CuveCAM', NULL, NULL, 120, 0),
('IMAGE', 'CuveCIMENT', 'Cuve Ciment', 'edvimgCuveCIMENT', NULL, 'CuveCIMENT', NULL, NULL, 102, 0),
('IMAGE', 'CuveEMA', 'Cuve Email', 'edvimgCuveEMAIL', NULL, 'CuveEMA', NULL, NULL, 102, 0),
('IMAGE', 'CuveEPOX', 'Cuve Epoxy', 'edvimgCuveEPOX', NULL, 'CuveEPOX', NULL, NULL, 102, 0),
('IMAGE', 'CuveFIBR', 'Cuve Fibre', 'edvimgCuveFIBR', NULL, 'CuveFIBR', NULL, NULL, 102, 0),
('IMAGE', 'CuveFUT', 'Fût', 'edvimgCuveFUT', NULL, 'Cuve', NULL, NULL, 109, 0),
('IMAGE', 'CuveINOX', 'Cuve Inox', 'edvimgCuveINOX', NULL, 'CuveINOX', NULL, NULL, 102, 0),
('IMAGE', 'CuvePRESS', 'Pressoir', 'edvimgCuvePRESS', NULL, 'CuvePRESS', NULL, NULL, 110, 0),
('IMAGE', 'CuveRESIN', 'Cuve Résine', 'edvimgCuveRESIN', NULL, 'CuveRESIN', NULL, NULL, 103, 0),
('IMAGE', 'Cuves', 'Cuves', 'edvimgCuves', NULL, 'Cuves', NULL, NULL, 101, 0),
('IMAGE', 'DateTime', 'Date', NULL, NULL, 'DateTime', NULL, NULL, 9999, 0),
('IMAGE', 'Domain', 'Domaine', 'imgEDVTypeDomain', '', 'Domain', '', '', 1, 0),
('IMAGE', 'DomSys', 'Dossier système', 'imgEDVTypeDomSys', '', 'DomSys', '', '', 2, 0),
('IMAGE', 'Dossier', 'Dossier', 'edvimgDossier', NULL, 'Dossier', NULL, NULL, 9999, 0),
('IMAGE', 'Dossiers', 'Dossiers', 'edvimgDossiers', NULL, 'Dossiers', NULL, NULL, 9999, 0),
('IMAGE', 'EDV', 'EDV', NULL, NULL, 'EDV', NULL, NULL, 9999, 0),
('IMAGE', 'Fourn', 'Fournisseur', 'edvimgFourn', NULL, 'Fourn', NULL, NULL, 3000, 0),
('IMAGE', 'Image', 'Image', NULL, NULL, 'Image', NULL, NULL, 9999, 0),
('IMAGE', 'IN', 'Entrant', 'edvimgArrowIN', NULL, 'IN', NULL, NULL, 1001, 0),
('IMAGE', 'Info', 'Info', 'edvimgInfo', NULL, 'Info', NULL, NULL, 9999, 0),
('IMAGE', 'LgExec', 'Tâche à faire', 'edvimgLgExec', NULL, 'LgExec', NULL, NULL, 9999, 0),
('IMAGE', 'Location', 'Localisation', 'edvimgLocation', NULL, 'Location', NULL, NULL, 9999, 0),
('IMAGE', 'Num', 'Nombre', NULL, NULL, 'Num', NULL, NULL, 9999, 0),
('IMAGE', 'Object', 'Objet', NULL, NULL, 'Object', NULL, NULL, 9999, 0),
('IMAGE', 'Ok', 'Ok', 'imgEDVTypeTrue', NULL, 'Ok', NULL, NULL, 9901, 0),
('IMAGE', 'Oper', 'Opération', 'imgEDVTypeNull', NULL, 'Oper', NULL, NULL, 9999, 0),
('IMAGE', 'OUT', 'Sortant', 'edvimgArrowOUT', NULL, 'OUT', NULL, NULL, 1101, 0),
('IMAGE', 'Print', 'Impression', NULL, NULL, 'Print', NULL, NULL, 9999, 0),
('IMAGE', 'ProdVin', 'Produit vinicole', 'edvimgProdVin', NULL, 'ProdVin', NULL, NULL, 200, 0),
('IMAGE', 'ProdVin.B', 'Vin blanc', 'edvimgProdVinB', '', 'ProdVin.B', '', '', 210, 0),
('IMAGE', 'ProdVin.C', 'Vin crémant', 'edvimgProdVinC', NULL, 'ProdVin.C', NULL, NULL, 211, 0),
('IMAGE', 'ProdVin.O', 'Vin rosé', 'edvimgProdVinO', NULL, 'ProdVin.O', NULL, NULL, 212, 0),
('IMAGE', 'ProdVin.R', 'Vin rouge', 'edvimgProdVinR', NULL, 'ProdVin.R', NULL, NULL, 213, 0),
('IMAGE', 'Selection', 'Sélection', NULL, NULL, 'Selection', NULL, NULL, 9999, 0),
('IMAGE', 'Site', 'Site', 'edvimgSite', NULL, 'Site', NULL, NULL, 9999, 0),
('IMAGE', 'Text', 'Texte', 'imgEDVTypeText', NULL, 'Text', NULL, NULL, 5, 0),
('IMAGE', 'Tree', 'Arbre', 'edvimgTree', NULL, 'Tree', NULL, NULL, 9999, 0),
('IMAGE', 'User', 'Utilisateur', 'edvimgUser', NULL, 'User', NULL, NULL, 9999, 0),
('Q', 'Q.P', 'Paramètres de requête', NULL, NULL, NULL, NULL, NULL, 1, 0),
('Q.P', 'SQL', 'SQL', NULL, NULL, NULL, NULL, NULL, 1, 0),
('Q.P.SQL', 'DELETE', 'DELETE', NULL, NULL, NULL, NULL, NULL, 1, 0),
('Q.P.SQL', 'INSERT', 'INSERT', NULL, NULL, NULL, NULL, NULL, 1, 0),
('Q.P.SQL', 'SELECT', 'SELECT', NULL, NULL, NULL, NULL, 'SELECT *\r\nFROM table', 1, 0),
('Q.P.SQL', 'UPDATE', 'UPDATE', NULL, NULL, NULL, NULL, NULL, 1, 0),
('UNIT', '', ' ', NULL, 'Text', 'Text', NULL, NULL, -1, 0),
('UNIT', '%', '%', NULL, 'Double', 'Num', NULL, NULL, 7, 0),
('UNIT', 'bt', 'boite(s)', NULL, 'Double', 'Num', NULL, NULL, 4, 0),
('UNIT', 'g', 'g', NULL, 'Double', 'Num', NULL, NULL, 2, 0),
('UNIT', 'H', 'Heure', NULL, 'Double', 'DateTime', NULL, NULL, 5, 0),
('UNIT', 'J', 'Jour', NULL, 'Double', 'DateTime', NULL, NULL, 6, 0),
('UNIT', 'Kg', 'Kg', NULL, 'Double', 'Num', NULL, NULL, 1, 0),
('UNIT', 'L', 'L', NULL, 'Double', 'Num', NULL, NULL, 0, 0),
('UNIT', 'mg', 'mg', NULL, 'Double', 'Num', NULL, NULL, 2, 0),
('UNIT', 'u', 'unité', NULL, 'Double', 'Num', NULL, NULL, 4, 0),
('USER', 'USER.TYPE', 'Type d''utilisateur', NULL, NULL, NULL, NULL, NULL, 9999, 0),
('USER.TYPE', '1', 'Système', NULL, NULL, NULL, NULL, NULL, 9999, 0),
('USER.TYPE', '16', 'Responsable de cave', NULL, NULL, NULL, NULL, NULL, 4, 0),
('USER.TYPE', '4', 'Administrateur', NULL, NULL, NULL, NULL, NULL, 8, 0),
('USER.TYPE', '64', 'Utilisateur', NULL, NULL, NULL, NULL, NULL, 0, 0),
('VALUETYPE', ' ', '-', '', '', 'Null', '', NULL, -1, 0),
('VALUETYPE', 'Boolean', 'Oui/Non', '', 'Boolean', 'Boolean', '', NULL, 2, 0),
('VALUETYPE', 'DateTime', 'Date/Heure', '', 'DateTime', 'DateTime', '', NULL, 6, 0),
('VALUETYPE', 'Double', 'Numérique', '0', 'Double', 'Num', '', NULL, 0, 0),
('VALUETYPE', 'Image', 'Image', '', 'Image', 'Image', '', NULL, 999, 0),
('VALUETYPE', 'Selection', 'Sélection', '', 'Selection', 'Selection', '', NULL, 5, 0),
('VALUETYPE', 'Text', 'Texte', '', 'Text', 'Text', '', NULL, 1, 0);

-- --------------------------------------------------------

--
-- Structure de la table `rights`
--

DROP TABLE IF EXISTS `rights`;
CREATE TABLE IF NOT EXISTS `rights` (
  `UserType` int(11) NOT NULL,
  `Domain` varchar(256) NOT NULL,
  `Rights` int(8) NOT NULL,
  `Data` varchar(1024) DEFAULT NULL,
  PRIMARY KEY (`UserType`,`Domain`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `rights`
--

INSERT INTO `rights` (`UserType`, `Domain`, `Rights`, `Data`) VALUES
(1, 'Admin', 15, NULL),
(1, 'Article', 15, NULL),
(1, 'Client', 15, NULL),
(1, 'design', 15, NULL),
(1, 'Devis', 15, NULL),
(1, 'Dossier', 15, NULL),
(1, 'Remise', 15, NULL),
(4, 'Admin', 15, NULL),
(4, 'Article', 15, NULL),
(4, 'Client', 15, NULL),
(4, 'Devis', 15, NULL),
(4, 'Dossier', 15, NULL),
(4, 'Remise', 15, NULL),
(16, 'Admin', 0, NULL),
(16, 'Client', 15, NULL),
(16, 'Devis', 15, NULL),
(16, 'Dossier', 15, NULL),
(16, 'Remise', 15, NULL),
(64, 'Admin', 0, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `rightsdomain`
--

DROP TABLE IF EXISTS `rightsdomain`;
CREATE TABLE IF NOT EXISTS `rightsdomain` (
  `Domain` varchar(256) NOT NULL,
  `Name` varchar(256) NOT NULL,
  PRIMARY KEY (`Domain`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `rightsdomain`
--

INSERT INTO `rightsdomain` (`Domain`, `Name`) VALUES
('Admin', 'Administration'),
('Article', 'Gestion des articles'),
('Client', 'Gestion des clients'),
('Dossier', 'Gestion des dossiers');

-- --------------------------------------------------------

--
-- Structure de la table `tree_data`
--

DROP TABLE IF EXISTS `tree_data`;
CREATE TABLE IF NOT EXISTS `tree_data` (
  `id` int(10) unsigned NOT NULL,
  `nm` varchar(255) CHARACTER SET utf8 NOT NULL COMMENT 'nom',
  `typ` varchar(32) DEFAULT NULL COMMENT 'source externe',
  `ext` varchar(64) DEFAULT NULL COMMENT 'Clé dans la source externe',
  `params` text,
  `design` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Visible uniquement en mode Design',
  `icon` varchar(32) DEFAULT NULL COMMENT 'class de l''icône',
  `color` varchar(16) DEFAULT NULL,
  `ulvl` int(11) NOT NULL DEFAULT '0' COMMENT 'User level',
  `user` int(11) NOT NULL DEFAULT '0' COMMENT 'private for user',
  PRIMARY KEY (`id`),
  KEY `typ` (`typ`),
  KEY `ext` (`ext`),
  KEY `ulvl` (`ulvl`),
  KEY `user` (`user`),
  KEY `design` (`design`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `tree_data`
--

INSERT INTO `tree_data` (`id`, `nm`, `typ`, `ext`, `params`, `design`, `icon`, `color`, `ulvl`, `user`) VALUES
(0, 'SQL', NULL, NULL, NULL, 1, NULL, NULL, 0, 0),
(1, 'edQ', 'folder', '', '', 1, 'file file-folder-sys', NULL, 0, 0),
(1065, 'UPDATE', 'sql', '', 'UPDATE maTable\nSET name = :NAME\nWHERE id = :ID\nLIMIT 0, 1', 1, NULL, NULL, 0, 0),
(1069, 'TerraFact', 'folder', '', '', 0, 'file file-folder', '', 0, 0),
(1070, 'DB', 'folder', '', '', 1, NULL, NULL, 0, 0),
(1074, '_templates', 'folder', '', '', 1, 'file file-folder-sys', 'null', 0, 0),
(1078, 'css', 'css', '', '', 1, NULL, NULL, 0, 0),
(1094, 'SELECT', 'sql', '', 'SELECT *\nFROM maTable\nWHERE id = :ID\nORDER BY name\nLIMIT 0, 10', 1, 'file file-sql', NULL, 0, 0),
(1095, 'SQL', 'folder', '', '', 1, NULL, NULL, 0, 0),
(1097, 'INSERT', 'sql', '', 'INSERT INTO maTable (id, name)\nVALUES( :ID, :NAME )\nLIMIT 0, 1', 1, NULL, NULL, 0, 0),
(1098, 'DELETE', 'sql', '', 'DELETE FROM maTable\nWHERE id = :ID\nLIMIT 0, 1', 1, NULL, NULL, 0, 0),
(1100, 'Contacts', 'folder', '', '', 0, 'file file-folder', '', 0, 0),
(1101, 'Liste', 'php', '', '', 1, 'file file-query', NULL, 2, 1),
(1102, 'Edition', 'php', '', '', 1, 'file file-query', NULL, 2, 1),
(1103, 'dataSource', 'dataSource', '', '', 1, 'file file-iso', NULL, 2, 1),
(1104, 'html', 'html', '', '', 1, 'file file-file', NULL, 0, 0),
(1105, 'css', 'css', '', '', 1, NULL, NULL, 0, 0),
(1106, 'html', 'html', '', '', 1, 'file file-file', NULL, 0, 0),
(1107, 'php', 'php', '', '', 1, 'file file-php', NULL, 0, 0),
(1111, 'dataSource', 'dataSource', '', '', 1, 'file file-iso', NULL, 0, 0),
(1121, 'Xml', 'html', '', '', 0, 'file file-iso', '', 0, 0),
(1123, 'Requête', 'query', '', '', 1, 'file file-query', NULL, 2, 1),
(1133, 'rezo', 'folder', '', '', 0, 'file file-folder', '', 0, 0),
(1134, 'Requête', 'query', '', '', 1, 'file file-query', NULL, 2, 1),
(1157, 'Test', 'query', '', '', 1, 'file file-query', NULL, 2, 1),
(1158, '_System', 'folder', '', '', 1, 'file file-folder-sys', '#ebdab9', 0, 1),
(1159, 'Paramètres', 'folder', '', '', 1, 'file file-folder', '#ffffff', 0, 0),
(1160, 'Liste', 'php', '', '', 1, 'file file-query', '#f0b2f0', 2, 1),
(1161, 'Edition', 'php', '', '', 1, 'file file-query', '#c1deab', 2, 1),
(1162, 'Requête', 'query', '', '', 1, 'file file-query', '#eaed91', 2, 1),
(1166, 'dataSource', 'dataSource', '', '', 1, 'file file-iso', '', 0, 0),
(1167, '_Exemples', 'folder', '', '', 1, 'file file-folder-sys', NULL, 0, 0),
(1168, 'dataTable', 'folder', '', '', 1, 'file file-folder', '', 0, 0),
(1169, 'Simple', 'folder', '', '', 1, 'file file-folder', NULL, 0, 0),
(1170, 'Zéro config', 'php', '', '', 1, 'file file-php', NULL, 0, 0),
(1171, 'data', 'folder', '', '', 1, 'file file-file', NULL, 0, 0),
(1172, 'Ajax', 'folder', '', '', 1, 'file file-folder', NULL, 0, 0),
(1173, 'Array', 'php', '', '', 1, 'file file-php', NULL, 0, 0),
(1174, 'data', 'folder', '', '', 1, 'file file-file', NULL, 0, 0),
(1175, 'html', NULL, NULL, NULL, 1, NULL, NULL, 0, 0),
(1176, 'Objets JSON', 'php', '', '', 1, 'file file-php', NULL, 0, 0),
(1177, 'data', 'folder', '', '', 1, 'file file-file', NULL, 0, 0),
(1178, 'html', NULL, NULL, NULL, 1, NULL, NULL, 0, 0),
(1179, 'Objets complexes', 'php', '', '', 1, 'file file-php', NULL, 0, 0),
(1180, 'data', 'folder', '', '', 1, 'file file-file', NULL, 0, 0),
(1181, 'html', NULL, NULL, NULL, 1, NULL, NULL, 0, 0),
(1182, 'Details', 'php', '', '', 1, 'file file-php', NULL, 0, 0),
(1183, 'data', 'folder', '', '', 1, 'file file-file', NULL, 0, 0),
(1184, 'html', NULL, NULL, NULL, 1, NULL, NULL, 0, 0),
(1185, 'css', 'css', '', '', 1, 'file file-css', NULL, 0, 0),
(1186, 'images', 'folder', '', '', 1, 'file file-folder', NULL, 0, 0),
(1187, 'jquery', 'folder', '', '', 1, 'file file-folder', NULL, 0, 0),
(1188, 'jqGrid', 'folder', '', '', 1, 'file file-folder', NULL, 0, 0),
(1189, 'Hide Grouping Column', 'php', '', '', 1, 'file file-php', NULL, 0, 0),
(1190, 'data', 'folder', '', '', 1, 'file file-file', NULL, 0, 0),
(1191, 'html', NULL, NULL, NULL, 1, NULL, NULL, 0, 0),
(1192, 'css', 'css', '', '', 1, 'file file-css', NULL, 0, 0),
(1194, 'functions', 'default', '', '', 1, 'file file-folder-sys', '', 0, 0),
(1195, 'execute', 'default', '', '', 1, 'file file-file', '', 0, 0),
(1196, 'get_callstack', 'default', '', '', 1, 'file file-file', '', 0, 0),
(1198, 'arguments', NULL, NULL, NULL, 1, NULL, NULL, 0, 0),
(1199, 'javascript', 'default', '', '', 1, 'file file-folder-sys', '', 0, 0),
(1200, 'view dialog', 'default', '', '', 1, 'file file-file', '', 0, 0),
(1202, 'call', 'default', '', '', 1, 'file file-file', '', 0, 0),
(1203, 'subpage', 'html', '', '', 1, 'file file-htm', '', 0, 0),
(1204, 'Xml', 'html', '', '', 0, 'file file-file', '', 0, 0),
(1205, 'Load, Save', 'default', '', '', 1, 'file file-file', '', 0, 0),
(1208, 'Async+Sort', 'php', '', '', 1, 'file file-php', '', 0, 0),
(1209, 'data', 'folder', '', '', 1, 'file file-file', NULL, 0, 0),
(1210, 'html', NULL, NULL, NULL, 1, NULL, NULL, 0, 0),
(1211, 'css', 'css', '', '', 1, 'file file-css', NULL, 0, 0),
(1215, 'Fichiers .php residuels', 'default', '', '', 1, 'file file-file', '', 0, 0),
(1216, 'Pages', 'folder', '', '', 1, 'file file-folder', 'false', 0, 0),
(1218, 'Noeuds', 'default', '', '', 1, 'file file-file', '', 0, 0),
(1220, 'Editeurs', 'folder', '', '', 1, 'file file-folder', '', 0, 0),
(1221, 'CodeMirror', NULL, NULL, NULL, 1, NULL, NULL, 0, 0),
(1222, 'test', 'default', '', '', 1, 'file file-php', '', 0, 0),
(1229, 'Utilisateurs', 'folder', '', '', 1, 'file file-folder', '', 0, 0),
(1230, 'Liste', 'php', '', '', 1, 'file file-query', '', 0, 0),
(1231, 'Edition', 'php', '', '', 1, 'file file-query', NULL, 2, 1),
(1232, 'Convertisseurs', 'folder', '', '', 1, 'file file-folder', '', 0, 0),
(1233, 'table', 'html', '', '', 1, 'file file-htm', '', 0, 0),
(1234, 'csv', 'php', '', '', 1, 'file file-php', '', 0, 0),
(1235, 'sub', NULL, NULL, NULL, 1, NULL, NULL, 0, 0),
(1236, 'rows', 'php', '', '', 1, 'file file-query', '', 0, 0),
(1239, 'Gestion - Compta', 'folder', '', '', 0, 'file file-folder', '#d5debf', 0, 0),
(1240, 'Temps de travail', 'folder', '', '', 0, 'file file-folder', '', 0, 0),
(1241, 'Analytique', 'php', '', '', 0, 'file file-query', '', 0, 0),
(1247, 'Details', 'php', '', '', 0, 'file file-query', '', 0, 0),
(1248, 'Analytique par contexte', 'php', '', '', 0, 'file file-query', '', 0, 0),
(1249, 'dataSource', 'php', NULL, NULL, 1, NULL, NULL, 0, 0),
(1250, 'TODO', 'html', '', '', 1, 'file file-file', '', 0, 0),
(1251, 'url', 'default', '', '', 1, 'file file-file', '', 0, 0),
(1319, 'Sauvegarde', 'folder', NULL, NULL, 0, 'file file-folder', NULL, 0, 0),
(1320, 'zip', 'php', '', '', 0, 'file file-iso', '', 0, 0),
(1321, 'Divers', 'folder', '', '', 1, 'file file-folder', '', 0, 0),
(1322, 'debug', 'folder', '', '', 1, 'file file-folder', '', 0, 0),
(1323, 'dialog', 'php', NULL, NULL, 0, 'file file-php', NULL, 0, 0),
(1324, 'server', 'php', NULL, NULL, 0, 'file file-php', NULL, 0, 0),
(1325, 'get', 'php', NULL, NULL, 0, 'file file-php', NULL, 0, 0),
(1326, 'Utilisateur', 'folder', '', '', 1, 'file file-folder', '', 0, 0),
(1327, 'Preferences', 'folder', NULL, NULL, 0, 'file file-folder', NULL, 0, 0),
(1328, 'session', 'php', '', '', 0, 'file file-php', '', 0, 0),
(1329, 'dialog', 'php', NULL, NULL, 0, 'file file-php', NULL, 0, 0),
(1331, 'set', 'php', '', '', 0, 'file file-php', '', 0, 0),
(1341, 'Filters', 'php', NULL, NULL, 1, NULL, NULL, 0, 0),
(1342, 'UI', 'folder', NULL, NULL, 0, 'file file-folder', NULL, 0, 0),
(1344, 'Layout', 'js', '', '', 0, 'file file-js', '', 0, 0),
(1345, '_html', 'folder', '', '', 1, 'file file-folder-sys', '', 0, 0),
(1346, 'input', 'folder', NULL, NULL, 0, 'file file-folder', NULL, 0, 0),
(1347, 'file', 'html', NULL, NULL, 0, 'file file-html', NULL, 0, 0),
(1348, 'table', 'folder', NULL, NULL, 0, 'file file-folder', NULL, 0, 0),
(1349, 'csv', 'php', NULL, NULL, 0, 'file file-php', NULL, 0, 0),
(1350, 'rows', 'php', NULL, NULL, 0, 'file file-php', NULL, 0, 0),
(1351, 'demo', 'default', NULL, NULL, 0, 'file file-file', NULL, 0, 0),
(1354, 'Ecritures', 'php', NULL, NULL, 0, 'file file-php', NULL, 0, 0),
(1355, 'csv', 'default', NULL, NULL, 0, 'file file-file', NULL, 0, 0),
(1356, 'Recherche', 'default', '', '', 1, 'file file-iso', '', 0, 0),
(1357, 'phpinfo', 'php', '', '', 0, 'file file-php', '', 0, 0),
(1360, 'form', 'php', '', '', 0, 'file file-htm', '', 0, 0),
(1361, 'dataTable', 'php', '', '', 0, 'file file-php', '', 0, 0),
(1362, 'demo', 'default', NULL, NULL, 0, 'file file-file', NULL, 0, 0),
(1363, 'sdn.org', 'folder', NULL, NULL, 0, 'file file-folder', NULL, 0, 0),
(1364, 'Synthese', 'php', NULL, NULL, 1, NULL, NULL, 0, 0),
(1365, 'PostFix', 'folder', NULL, NULL, 1, NULL, NULL, 0, 0),
(1366, 'conf', 'php', NULL, NULL, 1, NULL, NULL, 0, 0),
(1367, 'logs', 'php', NULL, NULL, 1, NULL, NULL, 0, 0),
(1368, 'section', 'php', NULL, NULL, 1, NULL, NULL, 0, 0),
(1369, 'sections', 'php', NULL, NULL, 1, NULL, NULL, 0, 0),
(1370, 'page', 'php', '', '', 1, 'file file-c', '', 0, 0),
(1371, 'image', 'folder', NULL, NULL, 0, 'file file-folder', NULL, 0, 0),
(1372, 'jquery-ui', 'php', NULL, NULL, 0, 'file file-php', NULL, 0, 0),
(1373, 'edQ', 'php', NULL, NULL, 0, 'file file-php', NULL, 0, 0),
(1374, 'helpers', 'php', '', '', 1, 'file file-c', '', 0, 0),
(1377, 'Sources', 'folder', '', '', 1, 'file file-folder', '', 0, 0),
(1378, 'Recherche', 'default', '', '', 1, 'file file-iso', '', 0, 0),
(1383, 'Favoris', 'html', NULL, NULL, 1, 'file file-html', NULL, 0, 0),
(1384, 'delete', 'php', '', '', 0, 'file file-php', '', 0, 0),
(1385, 'tree', 'php', '', '', 1, 'file file-c', '', 0, 0),
(1386, '_docs', 'folder', '', '', 1, 'file file-folder-sys', '#ffffff', 0, 0),
(1387, 'php', 'folder', NULL, NULL, 1, 'file file-folder', NULL, 0, 0),
(1388, 'Version', 'php', NULL, NULL, 1, 'file file-php', NULL, 0, 0),
(1389, 'dev', 'html', NULL, NULL, 1, 'file file-html', NULL, 0, 0),
(1390, 'Recherche', 'default', '', '', 1, 'file file-iso', '', 0, 0),
(1391, 'render as function', 'folder', '', '', 1, 'file file-folder', '', 0, 0),
(1392, 'submit', 'php', NULL, NULL, 1, 'file file-php', NULL, 0, 0),
(1393, 'cast to boolean', 'default', NULL, NULL, 1, 'file file-file', NULL, 0, 0),
(1394, 'vTigerCRM', 'folder', '', '', 1, 'file file-folder', '', 0, 0),
(1395, 'Dev', 'folder', NULL, NULL, 1, 'file file-folder', NULL, 0, 0),
(1396, 'Module', 'folder', NULL, NULL, 1, 'file file-folder', NULL, 0, 0),
(1397, 'Notes', 'default', NULL, NULL, 1, 'file file-file', NULL, 0, 0),
(1398, 'vTiger', 'folder', NULL, NULL, 1, 'file file-folder', NULL, 0, 0),
(1399, 'CRM', 'folder', NULL, NULL, 1, 'file file-folder', NULL, 0, 0),
(1400, 'Translations', 'default', '', '', 1, 'file file-file', '', 0, 0),
(1401, 'autres modifs', 'default', '', '', 1, 'file file-file', '', 0, 0),
(1402, 'Todo', 'default', NULL, NULL, 1, 'file file-file', NULL, 0, 0),
(1403, 'Outils', 'default', NULL, NULL, 1, 'file file-file', NULL, 0, 0);

-- --------------------------------------------------------

--
-- Structure de la table `tree_struct`
--

DROP TABLE IF EXISTS `tree_struct`;
CREATE TABLE IF NOT EXISTS `tree_struct` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `lft` int(10) unsigned NOT NULL,
  `rgt` int(10) unsigned NOT NULL,
  `lvl` int(10) unsigned NOT NULL,
  `pid` int(10) unsigned NOT NULL,
  `pos` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1404 ;

--
-- Contenu de la table `tree_struct`
--

INSERT INTO `tree_struct` (`id`, `lft`, `rgt`, `lvl`, `pid`, `pos`) VALUES
(1, 1, 314, 0, 0, 0),
(1065, 76, 77, 3, 1095, 2),
(1069, 2, 21, 1, 1, 0),
(1070, 19, 20, 2, 1069, 5),
(1074, 62, 87, 1, 1, 3),
(1078, 67, 68, 2, 1074, 2),
(1094, 78, 79, 3, 1095, 3),
(1095, 71, 80, 2, 1074, 4),
(1097, 74, 75, 3, 1095, 1),
(1098, 72, 73, 3, 1095, 0),
(1100, 11, 18, 2, 1069, 4),
(1101, 16, 17, 3, 1100, 2),
(1102, 14, 15, 3, 1100, 1),
(1103, 81, 82, 2, 1074, 5),
(1104, 65, 66, 2, 1074, 1),
(1105, 9, 10, 2, 1069, 3),
(1106, 7, 8, 2, 1069, 2),
(1107, 63, 64, 2, 1074, 0),
(1111, 5, 6, 2, 1069, 1),
(1121, 42, 47, 3, 1321, 0),
(1123, 12, 13, 3, 1100, 0),
(1127, 44, 43, 3, 1121, 1),
(1133, 22, 49, 1, 1, 1),
(1134, 69, 70, 2, 1074, 3),
(1157, 3, 4, 2, 1069, 0),
(1158, 88, 155, 1, 1, 4),
(1159, 134, 141, 3, 1216, 0),
(1160, 137, 138, 4, 1159, 1),
(1161, 139, 140, 4, 1159, 2),
(1162, 135, 136, 4, 1159, 0),
(1166, 89, 90, 2, 1158, 0),
(1167, 220, 313, 1, 1, 7),
(1168, 226, 267, 3, 1187, 0),
(1169, 247, 260, 4, 1168, 1),
(1170, 248, 251, 5, 1169, 0),
(1171, 249, 250, 6, 1170, 0),
(1172, 227, 246, 4, 1168, 0),
(1173, 240, 245, 5, 1172, 2),
(1174, 241, 242, 6, 1173, 0),
(1175, 243, 244, 6, 1173, 1),
(1176, 234, 239, 5, 1172, 1),
(1177, 235, 236, 6, 1176, 0),
(1178, 237, 238, 6, 1176, 1),
(1179, 228, 233, 5, 1172, 0),
(1180, 229, 230, 6, 1179, 0),
(1181, 231, 232, 6, 1179, 1),
(1182, 252, 259, 5, 1169, 1),
(1183, 253, 254, 6, 1182, 0),
(1184, 255, 256, 6, 1182, 1),
(1185, 257, 258, 6, 1182, 2),
(1186, 261, 262, 4, 1168, 2),
(1187, 225, 286, 2, 1167, 1),
(1188, 268, 285, 3, 1187, 1),
(1189, 277, 284, 4, 1188, 1),
(1190, 278, 279, 5, 1189, 0),
(1191, 280, 281, 5, 1189, 1),
(1192, 282, 283, 5, 1189, 2),
(1194, 287, 292, 2, 1167, 2),
(1195, 163, 164, 4, 1370, 2),
(1196, 288, 289, 3, 1194, 0),
(1198, 161, 162, 4, 1370, 1),
(1199, 293, 296, 2, 1167, 3),
(1200, 294, 295, 3, 1199, 0),
(1202, 165, 168, 4, 1370, 3),
(1203, 166, 167, 5, 1202, 0),
(1204, 83, 86, 2, 1074, 6),
(1205, 84, 85, 3, 1204, 0),
(1208, 269, 276, 4, 1188, 0),
(1209, 270, 271, 5, 1208, 0),
(1210, 272, 273, 5, 1208, 1),
(1211, 274, 275, 5, 1208, 2),
(1215, 146, 147, 3, 1216, 3),
(1216, 133, 148, 2, 1158, 7),
(1218, 144, 145, 3, 1216, 2),
(1220, 297, 300, 2, 1167, 4),
(1221, 298, 299, 3, 1220, 0),
(1222, 221, 224, 2, 1167, 0),
(1229, 91, 98, 2, 1158, 1),
(1230, 92, 93, 3, 1229, 0),
(1231, 94, 97, 3, 1229, 1),
(1232, 301, 308, 2, 1167, 5),
(1233, 304, 307, 3, 1232, 1),
(1234, 305, 306, 4, 1233, 0),
(1235, 222, 223, 3, 1222, 0),
(1236, 302, 303, 3, 1232, 0),
(1239, 23, 40, 2, 1133, 0),
(1240, 24, 39, 3, 1239, 0),
(1241, 31, 34, 4, 1240, 2),
(1247, 28, 29, 5, 1248, 0),
(1248, 27, 30, 4, 1240, 1),
(1249, 25, 26, 4, 1240, 0),
(1250, 103, 104, 2, 1158, 3),
(1251, 159, 160, 4, 1370, 0),
(1319, 99, 102, 2, 1158, 2),
(1320, 100, 101, 3, 1319, 0),
(1321, 41, 48, 2, 1133, 1),
(1322, 105, 116, 2, 1158, 4),
(1323, 113, 114, 4, 1324, 0),
(1324, 112, 115, 3, 1322, 2),
(1325, 121, 122, 4, 1327, 1),
(1326, 117, 126, 2, 1158, 5),
(1327, 118, 125, 3, 1326, 0),
(1328, 108, 111, 3, 1322, 1),
(1329, 109, 110, 4, 1328, 0),
(1331, 119, 120, 4, 1327, 0),
(1341, 45, 46, 4, 1121, 0),
(1342, 127, 132, 2, 1158, 6),
(1344, 128, 129, 3, 1342, 0),
(1345, 194, 219, 1, 1, 6),
(1346, 195, 198, 2, 1345, 0),
(1347, 196, 197, 3, 1346, 0),
(1348, 199, 210, 2, 1345, 1),
(1349, 208, 209, 3, 1348, 1),
(1350, 200, 207, 3, 1348, 0),
(1351, 205, 206, 4, 1350, 1),
(1354, 35, 38, 4, 1240, 3),
(1355, 36, 37, 5, 1354, 0),
(1356, 142, 143, 3, 1216, 1),
(1357, 106, 107, 3, 1322, 0),
(1360, 211, 212, 2, 1345, 2),
(1361, 201, 204, 4, 1350, 0),
(1362, 202, 203, 5, 1361, 0),
(1363, 50, 61, 1, 1, 2),
(1364, 32, 33, 5, 1241, 0),
(1365, 51, 60, 2, 1363, 0),
(1366, 58, 59, 3, 1365, 1),
(1367, 52, 57, 3, 1365, 0),
(1368, 55, 56, 4, 1367, 1),
(1369, 53, 54, 4, 1367, 0),
(1370, 158, 169, 3, 1387, 0),
(1371, 213, 218, 2, 1345, 3),
(1372, 214, 215, 3, 1371, 0),
(1373, 216, 217, 3, 1371, 1),
(1374, 170, 171, 3, 1387, 1),
(1377, 149, 152, 2, 1158, 8),
(1378, 150, 151, 3, 1377, 0),
(1383, 130, 131, 3, 1342, 1),
(1384, 123, 124, 4, 1327, 2),
(1385, 172, 173, 3, 1387, 2),
(1386, 156, 193, 1, 1, 5),
(1387, 157, 174, 2, 1386, 0),
(1388, 153, 154, 2, 1158, 9),
(1389, 175, 176, 2, 1386, 1),
(1390, 264, 265, 5, 1391, 0),
(1391, 263, 266, 4, 1168, 3),
(1392, 95, 96, 4, 1231, 0),
(1393, 290, 291, 3, 1194, 1),
(1394, 177, 192, 2, 1386, 2),
(1395, 178, 191, 3, 1394, 0),
(1396, 183, 184, 4, 1395, 2),
(1397, 181, 182, 4, 1395, 1),
(1398, 310, 311, 3, 1399, 0),
(1399, 309, 312, 2, 1167, 6),
(1400, 179, 180, 4, 1395, 0),
(1401, 185, 186, 4, 1395, 3),
(1402, 187, 188, 4, 1395, 4),
(1403, 189, 190, 4, 1395, 5);

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `IdUser` int(11) NOT NULL COMMENT '= IdContact',
  `Enabled` tinyint(1) NOT NULL DEFAULT '1',
  `Password` varchar(64) CHARACTER SET utf8 NOT NULL,
  `UserType` smallint(4) NOT NULL,
  PRIMARY KEY (`IdUser`),
  KEY `Enabled` (`Enabled`),
  KEY `UserType` (`UserType`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `user`
--

INSERT INTO `user` (`IdUser`, `Enabled`, `Password`, `UserType`) VALUES
(1, 1, '*BE353D0D7826681F8B7C136ED9824915F5B99E7D', 1),
(715, 1, '*79E7BDAD506396E46282FF88F4526534A29E33C1', 64),
(720, 1, '*44460B3D4E8BE5B3263563F96B995BD33E444402', 4),
(722, 1, '*95DAD11F6C3D83D9E281195E792433EB05C15614', 4),
(742, 1, '*8C90040B372F63A4BB3B764BE90FD3EC38D2E227', 4),
(762, 1, '*CF5FEEE70223AC0CB8D559661D4145EA12DD033D', 4),
(763, 1, '*B1F9ACC9F58F4DA857A97AC2BA02FE1A51A82F32', 64),
(764, 1, '*6A00C25B7CD0860BF1F49C2FA85AFBDCC1B92865', 64),
(765, 1, '*75562E0C956E92996DA66DC7803FF4888035599A', 64);

-- --------------------------------------------------------

--
-- Structure de la table `user_param`
--

DROP TABLE IF EXISTS `user_param`;
CREATE TABLE IF NOT EXISTS `user_param` (
  `id` int(10) unsigned NOT NULL,
  `param` varchar(32) NOT NULL,
  `domain` varchar(32) NOT NULL,
  `value` text,
  `sortIndex` int(11) NOT NULL DEFAULT '999',
  PRIMARY KEY (`id`,`param`,`domain`),
  KEY `sortIndex` (`sortIndex`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `user_param`
--

INSERT INTO `user_param` (`id`, `param`, `domain`, `value`, `sortIndex`) VALUES
(1, 'DIV#favpanel', 'jstree-favpanel-nodes', '{"1356":{"h":24,"w":73,"x":64,"y":16,"text":"Recherche","icon":"file file-iso"},"1378":{"h":24,"w":73,"x":160,"y":16,"text":"Recherche","icon":"file file-iso"}}', 0),
(1, 'form', '_Pages/Recherche', '{"root":"\\/edQ\\/","content":""}', 0),
(1, 'form', '_Sources/Recherche', '{"root":"D:\\\\Wamp\\\\www\\\\mg.intranet\\\\","root_from_pages":false,"content":"L_ist","extensions":"php|tpl","exclude_paths":"test|packages|pkg|soap|logs|cache|tmp|sessions"}', 0);

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `node_comment`
--
ALTER TABLE `node_comment`
  ADD CONSTRAINT `node_comment_ibfk_1` FOREIGN KEY (`id`) REFERENCES `tree_data` (`id`);

--
-- Contraintes pour la table `node_param`
--
ALTER TABLE `node_param`
  ADD CONSTRAINT `node_param_ibfk_1` FOREIGN KEY (`id`) REFERENCES `tree_data` (`id`);
SET FOREIGN_KEY_CHECKS=1;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
