
-- structure en syntaxe MyIsam

drop database scin;
create database scin;

-- liste des centrales EDF suivies
CREATE TABLE centrale (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    centrale VARCHAR(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
    typeCentrale INT(1) NOT NULL
) ENGINE = MYISAM DEFAULT CHARSET=utf8;

ALTER TABLE centrale ADD INDEX (centrale);
 
-- table des installations suivies prioritairement
CREATE TABLE installation (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    installation VARCHAR(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL
) ENGINE = MYISAM DEFAULT CHARSET=utf8;

ALTER TABLE installation ADD INDEX (installation);

-- table des incidents EDF et ASN
CREATE TABLE incident (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    installation VARCHAR(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
    -- indiquer la foreignkey
    libelleInstallation VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
    gravite INT(1) NOT NULL ,
    titre VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
    -- dateIncident DATE NOT NULL,
    dateIncident VARCHAR(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
    texte TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
    texteDetail TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
    fichierTelecharge TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
    url TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
    page VARCHAR(5) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
    -- dateCapture DATETIME NOT NULL,
    dateCapture VARCHAR(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
    origine VARCHAR(5) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL
    -- deleted BOOLEAN NOT NULL DEFAULT 0
) ENGINE = MYISAM DEFAULT CHARSET=utf8;

ALTER TABLE incident ADD INDEX (installation);
ALTER TABLE incident ADD INDEX (dateIncident);
ALTER TABLE incident ADD INDEX (dateCapture);
ALTER TABLE incident ADD INDEX (origine);


-- table des décisions ASN
CREATE TABLE decisionsASN (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    idASN VARCHAR(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
    installation VARCHAR(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
    libelleInstallation VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
    dateDecision VARCHAR(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
    datePublication VARCHAR(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
    texte TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
    fichierTelecharge TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
    url TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
    -- dateCapture DATETIME NOT NULL,
    dateCapture VARCHAR(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL
) ENGINE = MYISAM DEFAULT CHARSET=utf8;

ALTER TABLE decisionsASN ADD INDEX (idASN);
ALTER TABLE decisionsASN ADD INDEX (installation);
ALTER TABLE decisionsASN ADD INDEX (dateDecision);
ALTER TABLE decisionsASN ADD INDEX (datePublication);
ALTER TABLE decisionsASN ADD INDEX (dateCapture);