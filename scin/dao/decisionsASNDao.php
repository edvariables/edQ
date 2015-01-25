<?php

/**
 * Description of incidentDao
 *
 * @author jm
 *
 *
 *
 * ED141106 : Mise à jour des enregistrements pour utilisation systématique du format AAAA/MM/JJ
 UPDATE `decisionsASN` SET `dateDecision`= REPLACE(`dateDecision`, '-', '/')
,`datePublication`= REPLACE(`datePublication`, '-', '/')
WHERE `datePublication` LIKE '%-%'
OR `dateDecision` LIKE '%-%'

 */
include_once '../config/Config.php';
include_once 'decisionsASN.php';
include_once 'decisionsASNMapper.php';

final class DecisionsASNDao extends DaoAbstract {

    function __construct() {
        if (self::DEBUG)
            echo html::parHtmlBal('Nouvel objet decisionsASNDao créé');
    }

    /*
     * Vider une table
     */

    public function truncateTable($table) {
        $sql = 'TRUNCATE TABLE ' . $table . ';';
        $nbr = $this->getDb()->exec($sql);
        return $nbr;
    }

    /* ----------------------------------
     * recherche sur identifiant unique
     * ---------------------------------- */

    public function daoRechDecisionsASNSuridASN($idASN) {
        $sql = 'SELECT * FROM decisionsASN ';
        $sql .= 'WHERE idASN="' . $idASN . '" ';
        if (self::DEBUG)
            echo html::parHtmlBal($sql);

        $row = $this->query($sql)->fetch();
        if (!$row) {
            return null;
        } else {
            return $row;
        }
    }

    /* ----------------------------------
     * 
     * ---------------------------------- */

    public function daoRechDecisionsASNSurDate($installation, $dateAMJ, $type = BaseDecision::DATEREF_EST_DATE_PUBLICATION) {

        if ($type == BaseDecision::DATEREF_EST_DATE_DECISION) {
            $date = 'dateDecision';
        } else {
            $date = 'datePublication';
        }
        $sql = 'SELECT * FROM decisionsASN ';
        $sql .= 'WHERE installation="' . $installation . '" ';
        $sql .= 'AND' . $date . '="' . $dateAMJ . '" ';
        if (self::DEBUG)
            echo html::parHtmlBal($sql);

        $row = $this->query($sql)->fetch();
        if (!$row) {
            return null;
        } else {
            return $row;
        }
    }

    /* ----------------------------------
     * 
     * ---------------------------------- */

    public function maxDateDecisionASN($type = 0) {
        if ($type == BaseDecision::DATEREF_EST_DATE_DECISION) {
            $date = 'dateDecision';
        } else {
            $date = 'datePublication';
        }
        $sql = 'SELECT 
            max(
                cast(                       
                    concat(
                        SUBSTRING(' . $date . ' FROM 1 FOR 4),
                        SUBSTRING(' . $date . ' FROM 6 FOR 2),
                        SUBSTRING(' . $date . ' FROM 9 FOR 2)
                    ) 
                as UNSIGNED
                ) 
            )
        FROM decisionsASN';
        //echo html::parHtmlBal($sql);

        $row = $this->query($sql)->fetch();
        if (!$row) {
            return null;
        } else {
            //var_dump($row);
            foreach ($row as $key => $dateNum) {
                //echo html::parHtmlBal($dateNum);
                $dateStr = strval($dateNum);
                //echo html::parHtmlBal($dateStr);
                $dateMaxAMJ = substr($dateStr, 0, 4) . "/" . substr($dateStr, 4, 2) . "/" . substr($dateStr, 6, 2);
                return $dateMaxAMJ;
            }
        }
    }

    /**
     * Rechercher les {@link DecisionsASN}s avec ordre de tri.
     * @return array tableau de {@link DecisionsASN}s
     */
    public function daoFind($order = 0, $champ = '', $valeur = '', $valeur2 = '') {
        //echo html::parHtmlBal('--- find');
        $result = array();
        foreach ($this->query($this->getFindSql($order, $champ, $valeur, $valeur2)) as $row) {
            //echo html::parHtmlBal('--- foreach');
            $decisionsASN = new DecisionsASN();
            DecisionsASNMapper::map($decisionsASN, $row);
            $result[$decisionsASN->getId()] = $decisionsASN;
        }
        return $result;
    }

    /**
     * Rechercher un {@link DecisionsASN} sur identifiant.
     * @return DecisionsASN DecisionsASN ou <i>null</i> si non trouvé
     */
    public function findById($id) {
        $row = $this->query('SELECT * FROM decisionsASN WHERE id = ' . (int) $id)->fetch();
        if (!$row) {
            return null;
        }
        $decisionsASN = new DecisionsASN();
        DecisionsASNMapper::map($decisionsASN, $row);
        return $decisionsASN;
    }

    /**
     * Save {@link DecisionsASN}.
     * @param DecisionsASN $decisionsASN {@link DecisionsASN} to be saved
     * @return DecisionsASN saved {@link DecisionsASN} instance
     */
    public function save(DecisionsASN $decisionsASN) {
        if (self::DEBUG)
            echo html::parHtmlBal('entrée dao->save');
        if ($decisionsASN->getId() === null) {
            return $this->insert($decisionsASN);
        }
        //return $this->update($decisionsASN);
        return $decisionsASN;
    }

    /**
     * Delete {@link DecisionsASN} by identifier.
     * @param int $id {@link DecisionsASN} identifier
     * @return bool <i>true</i> on success, <i>false</i> otherwise
     */
    public function delete($id) {
        // inutilisée
        return $id;
    }

    /* =======================================
     *  recheche un peu générique
     * (uniquement sur de l'alpha !!!)
     * ======================================= */

    const TRI_PAR_ID = 0;
    const TRI_PAR_INSTALLATION = 1;
    const TRI_PAR_DATEPUBLICATION = 2;
    const TRI_PAR_DATECAPTURE = 3;
    const TRI_PAR_DATEDECISION = 4;
    const TRI_PAR_DATEPUBLICATION_ET_DATEDECISION = 5;
    const TRI_PAR_INSTALLATION_ET_DATEPUBLICATION_DESC = 10;
    const TRI_PAR_INSTALLATION_ET_ID_ASC = 11;
//
    const WHERE_OR = 1;
    const WHERE_TRANCHE = 0; // plus tard

    private function getFindSql($order = 0, $champ = '', $valeur = '', $valeur2 = '', $type = self::WHERE_TRANCHE) {
        $sql = 'SELECT * FROM decisionsASN ';

        if ($valeur2 == '') {
// selection sur une valeur
            if (($champ != '') && ($valeur != '')) {
                $sql .= ' WHERE ' . $champ . '="' . $valeur . '" ';
            }
        } else {
// selection sur 2 valeurs
            if ($type == self::WHERE_TRANCHE) {
                // dans une tranche de valeur
                $sql .= ' WHERE ' . $champ . '>="' . $valeur . '" ';
                $sql .= ' AND ' . $champ . '<="' . $valeur2 . '" ';
            } else {
                // deux valeurs possibles (OR)
                $sql .= ' WHERE ' . $champ . '="' . $valeur . '" ';
                $sql .= ' OR ' . $champ . '="' . $valeur2 . '" ';
            }
        }

// ordre de tri
        $orderBy = 'id';

        switch ($order) {
            case DecisionsASNDao::TRI_PAR_ID:
                $orderBy = 'id';
                break;
            case DecisionsASNDao::TRI_PAR_INSTALLATION:
                $orderBy = 'installation';
                break;
            case DecisionsASNDao::TRI_PAR_DATEPUBLICATION:
                $orderBy = 'datePublication DESC';
                break;
            case DecisionsASNDao::TRI_PAR_DATECAPTURE:
                $orderBy = 'dateCapture DESC';
                break;
            case DecisionsASNDao::TRI_PAR_DATEDECISION:
                $orderBy = 'dateDecision DESC';
                break;
            case DecisionsASNDao::TRI_PAR_DATEPUBLICATION_ET_DATEDECISION:
                $orderBy = 'datePublication DESC, dateDecision DESC';
                break;
            case DecisionsASNDao::TRI_PAR_INSTALLATION_ET_DATEPUBLICATION_DESC:
                $orderBy = 'installation, datePublication DESC';
                break;
            case DecisionsASNDao::TRI_PAR_INSTALLATION_ET_ID_ASC:
                $orderBy = 'installation, id';
                break;
            default:
                throw new Exception('Pas d\'ordre de tri pour le numéro : ' . $order);
        }
        $sql .= ' ORDER BY ' . $orderBy;

        //echo html::parHtmlBal('chaine SQL = ' . $sql);
        return $sql;
    }

    /**
     * @return DecisionsASN
     * @throws Exception
     */
    private function insert(DecisionsASN $decisionsASN) {
        if (self::DEBUG)
            echo html::parHtmlBal('entrée dao->insert');
        $sql = '
            INSERT INTO decisionsASN (
                id, 
                idASN,  
                installation, 
                libelleInstallation, 
                dateDecision,
                datePublication, 
                texte, 
                fichierTelecharge, 
                url, 
                dateCapture
            )
            VALUES (
                :id, 
                :idASN,
                :installation, 
                :libelleInstallation,
                :dateDecision, 
                :datePublication, 
                :texte, 
                :fichierTelecharge, 
                :url, 
                :dateCapture
            )';

        return $this->execute($sql, $decisionsASN);
    }

    /**
     * @return DecisionsASN
     * @throws Exception
     * Normalement ne devrait pas etre utilisé
     */
    private function update(DecisionsASN $decisionsASN) {
        $sql = '
            UPDATE decisionsASN SET
                idASN = :idASN,
                installation = :installation,                
                libelleInstallation = :libelleInstallation,  
                dateDecision = :dateDecision,
                datePublication = :datePublication,
                texte = :texte,
                fichierTelecharge = :fichierTelecharge,
                url = :url,
                dateCapture = :dateCapture
            WHERE
                id = :id';
        return $this->execute($sql, $decisionsASN);
    }

    /*     * ==================================
     * @return DecisionsASN
     * @throws Exception
     * Introduction de l'installation, si pas automatique
     * ==================================== */

    private function updateInstallation(DecisionsASN $decisionsASN) {
        $sql = '
            UPDATE incident SET
                installation = :installation
            WHERE
                id = :id';
        return $this->execute($sql, $decisionsASN);
    }

    /*     * ==================================
     * @return DecisionsASN
     * @throws Exception
     * =================================== */

    private function execute($sql, DecisionsASN $decisionsASN) {
        //echo html::parHtmlBal('$sql='.$sql);
        if (self::DEBUG)
            echo html::parHtmlBal('entrée dao->execute');
        $statement = $this->getDb()->prepare($sql);
        $this->executeStatement($statement, $this->getParams($decisionsASN));
        if (!$decisionsASN->getId()) {
            return $this->findById($this->getDb()->lastInsertId());
        }
        if (!$statement->rowCount()) {
            throw new NotFoundException('La décision numéro "' . $decisionsASN->getId() . '" n\'existe pas.');
        }
        return $decisionsASN;
    }

    private function getParams(DecisionsASN $decisionsASN) {
        $params = array(
            ':id' => $decisionsASN->getId(),
            ':idASN' => $decisionsASN->getIdASN(),
            ':installation' => $decisionsASN->getInstallation(),
            ':libelleInstallation' => $decisionsASN->getLibelleInstallation(),
            ':dateDecision' => str_replace('-', '/', $decisionsASN->getDateDecision()),
            ':datePublication' => str_replace('-', '/', $decisionsASN->getDatePublication()),
            ':texte' => $decisionsASN->getTexte(),
            ':fichierTelecharge' => $decisionsASN->getFichierTelecharge(),
            ':url' => $decisionsASN->getUrl(),
            ':dateCapture' => $decisionsASN->getDateCapture()
        );

        return $params;
    }

}

?>
