<?php

/**
 * Description of incidentDao
 *
 * @author jm
 * 
 */
include_once '../config/Config.php';
include_once 'incident.php';
include_once 'incidentMapper.php';

final class IncidentDao extends DaoAbstract {

    function __construct() {
        if (self::DEBUG)
            echo html::parHtmlBal('Nouvel incidentDAO créé');
    }

    /* ----------------------------------
     * 
     * ---------------------------------- */

    public function daoRechIncidentSurDate($installation, $dateAMJ, $origine = 'EDF') {
        $sql = 'SELECT * FROM incident ';
        $sql .= 'WHERE installation="' . $installation . '" ';
        $sql .= 'AND dateIncident="' . $dateAMJ . '" ';
        $sql .= 'AND origine="' . $origine . '" ';
        //echo html::parHtmlBal($sql);

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

    public function maxDateIncident() {
        //max( cast(substring( alphanumeric_columnName, 2, len(alphanumeric_columnName)) as int)) from tablename
        $sql = 'SELECT 
            max(
                cast(                       
                    concat(
                        SUBSTRING(dateIncident FROM 1 FOR 4),
                        SUBSTRING(dateIncident FROM 6 FOR 2),
                        SUBSTRING(dateIncident FROM 9 FOR 2)
                    ) 
                as UNSIGNED
                ) 
            )
        FROM incident';
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
     * Rechercher les {@link Incident}s avec ordre de tri.
     * @return array tableau de {@link Incident}s
     */
    public function daoFind($order = 0, $champ = '', $valeur = '', $valeur2 = '') {
        //echo html::parHtmlBal('--- find');
        $result = array();
        foreach ($this->query($this->getFindSql($order, $champ, $valeur, $valeur2)) as $row) {
            //echo html::parHtmlBal('--- foreach');
            $incident = new Incident();
            IncidentMapper::map($incident, $row);
            $result[$incident->getId()] = $incident;
        }
        return $result;
    }

    /**
     * Rechercher un {@link Incident} sur identifiant.
     * @return Incident Incident ou <i>null</i> si non trouvé
     */
    public function findById($id) {
        $row = $this->query('SELECT * FROM incident WHERE id = ' . (int) $id)->fetch();
        if (!$row) {
            return null;
        }
        $incident = new Incident();
        IncidentMapper::map($incident, $row);
        return $incident;
    }

    /**
     * Save {@link Incident}.
     * @param Incident $incident {@link Incident} to be saved
     * @return Incident saved {@link Incident} instance
     */
    public function save(Incident $incident) {
        if (self::DEBUG)
            echo html::parHtmlBal('entrée dao->save');
        if ($incident->getId() === null) {
            return $this->insert($incident);
        }
        //return $this->update($incident);
        return $incident;
    }

    /**
     * Delete {@link Incident} by identifier.
     * @param int $id {@link Incident} identifier
     * @return bool <i>true</i> on success, <i>false</i> otherwise
     */
    public function delete($id) {
        // inutilisée
        return $id;
    }

    /* =======================================
     *  recherche un peu générique
     * (uniquement sur de l'alpha !!!)
     * ======================================= */

    const TRI_PAR_ID = 0;
    const TRI_PAR_CENTRALE = 1;
    const TRI_PAR_DATEINCIDENT = 2;
    const TRI_PAR_DATECAPTURE = 3;
    const TRI_PAR_CENTRALE_ET_DATEINCIDENT_DESC = 10;
    const TRI_PAR_CENTRALE_ET_ID_ASC = 11;
//
    const WHERE_OR = 1;
    const WHERE_TRANCHE = 0; // plus tard

    private function getFindSql($order = 0, $champ = '', $valeur = '', $valeur2 = '', $type = self::WHERE_TRANCHE) {
        $sql = 'SELECT * FROM incident ';

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
            case IncidentDao::TRI_PAR_ID:
                $orderBy = 'id';
                break;
            case IncidentDao::TRI_PAR_CENTRALE:
                $orderBy = 'installation';
                break;
            case IncidentDao::TRI_PAR_DATEINCIDENT:
                $orderBy = 'dateIncident DESC';
                break;
            case IncidentDao::TRI_PAR_DATECAPTURE:
                $orderBy = 'dateCapture DESC';
                break;
            case IncidentDao::TRI_PAR_CENTRALE_ET_DATEINCIDENT_DESC:
                $orderBy = 'installation, dateIncident DESC';
                break;
            case IncidentDao::TRI_PAR_CENTRALE_ET_ID_ASC:
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
     * @return Incident
     * @throws Exception
     */
    private function insert(Incident $incident) {
        if (self::DEBUG)
            echo html::parHtmlBal('entrée dao->insert');
        $sql = '
            INSERT INTO incident (
                id, 
                installation, 
                libelleInstallation, 
                gravite, 
                titre, 
                dateIncident, 
                texte, 
                texteDetail, 
                fichierTelecharge, 
                url, 
                page, 
                dateCapture,
                origine
            )
            VALUES (
                :id, 
                :installation, 
                :libelleInstallation, 
                :gravite, 
                :titre, 
                :dateIncident, 
                :texte, 
                :texteDetail, 
                :fichierTelecharge, 
                :url, 
                :page, 
                :dateCapture,
                :origine
            )';

        return $this->execute($sql, $incident);
    }

    /**
     * @return Incident
     * @throws Exception
     * Normalement ne devrait pas etre utilisé
     */
    private function update(Incident $incident) {
        $sql = '
            UPDATE incident SET
                installation = :installation,
                libelleInstallation = :libelleInstallation,
                gravite = :gravite,
                titre = :titre,
                dateIncident = :dateIncident,
                texte = :texte,
                texteDetail = :texteDetail,
                fichierTelecharge = :fichierTelecharge,
                url = :url,
                page = :page,
                dateCapture = :dateCapture,
                origine = :origine
            WHERE
                id = :id';
        return $this->execute($sql, $incident);
    }

    /*     * ==================================
     * @return Incident
     * @throws Exception
     * Introduction de l'installation,
     * si pas automatique
     * ==================================== */

    private function updateInstallation(Incident $incident) {
        $sql = '
            UPDATE incident SET
                installation = :installation
            WHERE
                id = :id';
        return $this->execute($sql, $incident);
    }

    /*     * ==================================
     * @return Incident
     * @throws Exception
     * =================================== */

    private function execute($sql, Incident $incident) {
        if (self::DEBUG)
            echo html::parHtmlBal('entrée dao->execute');
        $statement = $this->getDb()->prepare($sql);
        //  echo html::parHtmlBal($sql);
        //  var_dump($this->getParams($incident));
        $this->executeStatement($statement, $this->getParams($incident));
        if (!$incident->getId()) {
            return $this->findById($this->getDb()->lastInsertId());
        }
        if (!$statement->rowCount()) {
            throw new NotFoundException('L\incident numéro "' . $incident->getId() . '" n\'existe pas.');
        }
        return $incident;
    }

    private function getParams(Incident $incident) {
        $params = array(
            ':id' => $incident->getId(),
            ':installation' => $incident->getInstallation(),
            ':libelleInstallation' => $incident->getLibelleInstallation(),
            ':gravite' => $incident->getGravite(),
            ':titre' => $incident->getTitre(),
            ':dateIncident' => $incident->getDateIncident(), //  self::formatDateTime($incident->getDateIncident()),
            ':texte' => $incident->getTexte(),
            ':texteDetail' => $incident->getTexteDetail(),
            ':fichierTelecharge' => $incident->getFichierTelecharge(),
            ':url' => $incident->getUrl(),
            ':page' => $incident->getPage(),
            ':dateCapture' => $incident->getDateCapture(), // self::formatDateTime($incident->getDateCapture()),
            ':origine' => $incident->getOrigine()
        );

        return $params;
    }

}

?>
