-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Client: localhost
-- Généré le: Lun 02 Juin 2014 à 18:43
-- Version du serveur: 5.5.24-log
-- Version de PHP: 5.3.13

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: `edq`
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
(1, 0, '', 'QV', 'sys', '', '', '', '', '', '', '', 1),
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
(1123, 'Caption', 'query', '''Noeud : '' . $node->name', 999),
(1123, 'Columns', 'query', '"IdContact" => array(\n	 "text" => "#"\n	 , "pk" => true\n)\n, "Name" => array("text" => "Nom")\n, "EMail" => "EMail"\n, "Phone1" => array(\n	 "text" => function(){ return "Téléphone(s)"; }\n	 , "value" => function($row, $column){\n	 	 return $row["Phone1"] . ($row["Phone2"] == '''' ? '''' : '' - '' . $row["Phone2"]);\n	 })\n, "Phone2" => array("visible" => false)\n, "Address" => array("text" => "Adresse")\n, "ZipCode" => array("text" => "Code postal")\n, "City" => array("text" => "Ville")', 999),
(1123, 'Foot', 'query', 'function($node, $rows, $viewer){\n	 return ''<center>'' . count($rows) . '' ligne'' . (count($rows) > 1 ? ''s'' : '''') . ''</center>'';\n}', 999),
(1123, 'SQLDelete', 'query', 'DELETE FROM contact\nWHERE a.id = :ID', 999),
(1123, 'SQLInsert', 'query', 'INSERT INTO contact (id, name)\nVALUES(:ID, :NAME)', 999),
(1123, 'SQLSelect', 'query', 'SELECT IdContact, Name, `EMail`, `Phone1`, `Phone2`, `Address`, `ZipCode`, `City`\nFROM contact c\nWHERE c.Name <> ''''\nORDER BY c.Name\nLIMIT 0, 10', 999),
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
(1162, 'Columns', 'query', '"IdContact" => array(\n	 "text" => "#"\n	 , "pk" => true\n)\n, "Name" => array("text" => "Nom")\n, "EMail" => "EMail"\n, "Phone1" => array(\n	 "text" => function(){ return "Téléphone(s)"; }\n	 , "value" => function($row, $column){\n	 	 return $row["Phone1"] . ($row["Phone2"] == '''' ? '''' : '' - '' . $row["Phone2"]);\n	 })\n, "Phone2" => array("visible" => false)\n, "Address" => array("text" => "Adresse")\n, "ZipCode" => array("text" => "Code postal")\n, "City" => array("text" => "Ville")', 999),
(1162, 'Foot', 'query', 'function($node, $rows, $viewer){\n	 return ''<center>'' . count($rows) . '' ligne'' . (count($rows) > 1 ? ''s'' : '''') . ''</center>'';\n}', 999),
(1162, 'SQLDelete', 'query', 'DELETE FROM contact\nWHERE a.id = :ID', 999),
(1162, 'SQLInsert', 'query', 'INSERT INTO contact (id, name)\nVALUES(:ID, :NAME)', 999),
(1162, 'SQLSelect', 'query', 'SELECT IdContact, Name, `EMail`, `Phone1`, `Phone2`, `Address`, `ZipCode`, `City`\nFROM contact c\nWHERE c.Name <> ''''\nORDER BY c.Name\nLIMIT 0, 10', 999),
(1162, 'SQLUpdate', 'query', 'UPDATE contact\n	SET a.name = :NAME\nWHERE a.id = :ID', 999);

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
  `icon` varchar(32) DEFAULT NULL COMMENT 'class de l''icône',
  `ulvl` int(11) NOT NULL DEFAULT '0' COMMENT 'User level',
  `user` int(11) NOT NULL DEFAULT '0' COMMENT 'private for user',
  PRIMARY KEY (`id`),
  KEY `typ` (`typ`),
  KEY `ext` (`ext`),
  KEY `ulvl` (`ulvl`),
  KEY `user` (`user`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `tree_data`
--

INSERT INTO `tree_data` (`id`, `nm`, `typ`, `ext`, `params`, `icon`, `ulvl`, `user`) VALUES
(1, 'edQ', 'folder', '', '', 'file file-folder-sys', 0, 0),
(1065, 'UPDATE', 'sql', '', 'UPDATE maTable\nSET name = :NAME\nWHERE id = :ID\nLIMIT 0, 1', NULL, 0, 0),
(1069, 'TerraFact', 'folder', '', '', NULL, 0, 0),
(1070, 'DB', 'folder', '', '', NULL, 0, 0),
(1074, '_templates', 'folder', '', '', 'file file-folder-sys', 0, 0),
(1078, 'css', 'css', '', '', NULL, 0, 0),
(1094, 'SELECT', 'sql', '', 'SELECT *\nFROM maTable\nWHERE id = :ID\nORDER BY name\nLIMIT 0, 10', 'file file-sql', 0, 0),
(1095, 'SQL', 'folder', '', '', NULL, 0, 0),
(1097, 'INSERT', 'sql', '', 'INSERT INTO maTable (id, name)\nVALUES( :ID, :NAME )\nLIMIT 0, 1', NULL, 0, 0),
(1098, 'DELETE', 'sql', '', 'DELETE FROM maTable\nWHERE id = :ID\nLIMIT 0, 1', NULL, 0, 0),
(1100, 'Contacts', 'folder', '', '', 'file file-folder', 0, 0),
(1101, 'Liste', 'php', '', '', 'file file-query', 2, 1),
(1102, 'Edition', 'php', '', '', 'file file-query', 2, 1),
(1103, 'dataSource', 'dataSource', '', '', 'file file-iso', 2, 1),
(1104, 'html', 'html', '', '', 'file file-file', 0, 0),
(1105, 'css', 'css', '', '', NULL, 0, 0),
(1106, 'html', 'html', '', '', 'file file-file', 0, 0),
(1107, 'php', 'php', '', '', 'file file-php', 0, 0),
(1111, 'dataSource', 'dataSource', '', '', 'file file-iso', 0, 0),
(1117, 'Xml', 'html', '', '', 'file file-file', 0, 0),
(1119, 'Remove', 'html', '', '', 'file file-file', 0, 0),
(1121, 'Xml', 'html', '', '', 'file file-file', 0, 0),
(1123, 'Requête', 'query', '', '', 'file file-query', 2, 1),
(1132, 'Keep', NULL, NULL, NULL, NULL, 0, 0),
(1133, 'rezo', 'folder', '', '', 'file file-folder', 0, 0),
(1134, 'Requête', 'query', '', '', 'file file-query', 2, 1),
(1157, 'Test', 'query', '', '', 'file file-query', 2, 1),
(1158, '_System', 'folder', '', '', 'file file-folder-sys', 0, 1),
(1159, 'Paramètres des noeuds', 'folder', '', '', 'file file-folder', 0, 0),
(1160, 'Liste', 'php', '', '', 'file file-query', 2, 1),
(1161, 'Edition', 'php', '', '', 'file file-query', 2, 1),
(1162, 'Requête', 'query', '', '', 'file file-query', 2, 1),
(1166, 'dataSource', 'dataSource', '', '', 'file file-iso', 0, 0);

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1167 ;

--
-- Contenu de la table `tree_struct`
--

INSERT INTO `tree_struct` (`id`, `lft`, `rgt`, `lvl`, `pid`, `pos`) VALUES
(1, 1, 68, 0, 0, 0),
(1065, 44, 45, 3, 1095, 2),
(1069, 2, 21, 1, 1, 0),
(1070, 19, 20, 2, 1069, 5),
(1074, 30, 55, 1, 1, 2),
(1078, 35, 36, 2, 1074, 2),
(1094, 46, 47, 3, 1095, 3),
(1095, 39, 48, 2, 1074, 4),
(1097, 42, 43, 3, 1095, 1),
(1098, 40, 41, 3, 1095, 0),
(1100, 11, 18, 2, 1069, 4),
(1101, 16, 17, 3, 1100, 2),
(1102, 14, 15, 3, 1100, 1),
(1103, 49, 50, 2, 1074, 5),
(1104, 33, 34, 2, 1074, 1),
(1105, 9, 10, 2, 1069, 3),
(1106, 7, 8, 2, 1069, 2),
(1107, 31, 32, 2, 1074, 0),
(1111, 5, 6, 2, 1069, 1),
(1117, 51, 54, 2, 1074, 6),
(1119, 52, 53, 3, 1117, 0),
(1121, 23, 28, 2, 1133, 0),
(1123, 12, 13, 3, 1100, 0),
(1127, 25, 26, 4, 1121, 1),
(1132, 24, 25, 3, 1121, 0),
(1133, 22, 29, 1, 1, 1),
(1134, 37, 38, 2, 1074, 3),
(1157, 3, 4, 2, 1069, 0),
(1158, 56, 67, 1, 1, 3),
(1159, 57, 64, 2, 1158, 0),
(1160, 58, 59, 3, 1159, 0),
(1161, 62, 63, 3, 1159, 2),
(1162, 60, 61, 3, 1159, 1),
(1166, 65, 66, 2, 1158, 1);

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `IdUser` int(11) NOT NULL COMMENT '= IdContact',
  `Enabled` tinyint(1) NOT NULL DEFAULT '1',
  `Password` varchar(64) NOT NULL,
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
(715, 1, '*0DAFED0471477CC72647B6CBC5E941EB7C9EE2BF', 64),
(720, 1, '*44460B3D4E8BE5B3263563F96B995BD33E444402', 4),
(722, 1, '*95DAD11F6C3D83D9E281195E792433EB05C15614', 4),
(742, 1, '*8C90040B372F63A4BB3B764BE90FD3EC38D2E227', 4),
(762, 1, '*CF5FEEE70223AC0CB8D559661D4145EA12DD033D', 4),
(763, 1, '*B1F9ACC9F58F4DA857A97AC2BA02FE1A51A82F32', 64),
(764, 1, '*6A00C25B7CD0860BF1F49C2FA85AFBDCC1B92865', 64),
(765, 1, '*75562E0C956E92996DA66DC7803FF4888035599A', 64);

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

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
