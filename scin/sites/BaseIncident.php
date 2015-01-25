<?php

class BaseIncident extends BaseAbstract {

    function __construct() {
        //echo html::parHtmlBal('*** nouvel incident base');
    }

// 
    protected function alimenterInfos($url) {
        $i = 0;

        foreach ($this->listeObjets as $incident) { //  $id => 
            $i++;
            //echo html::parHtmlBal($i);
            $item = IncidentMapper::mapToArray($incident);
            $this->infosItem[] = $item;
        }
    }

    public function incidentsDUneInstallation($installation) {
        $dao = new IncidentDao();
        $this->listeObjets = $dao->daoFind(IncidentDao::TRI_PAR_DATEINCIDENT, 'installation', $installation);
        $this->paramsRecherche = $this->listeParametresRecherche('installation', $installation);
        $this->alimenterInfos('');
        unset($dao);
    }

    public function incidentsAUneDate($deDate, $aDate = '') {
        //echo html::parHtmlBal('de '.$deDate.' à '.$aDate);
        $dao = new IncidentDao();
        $this->listeObjets = $dao->daoFind(IncidentDao::TRI_PAR_CENTRALE_ET_DATEINCIDENT_DESC, 'dateIncident', $deDate, $aDate);
        $this->paramsRecherche = $this->listeParametresRecherche('dateIncident', $deDate, $aDate);
        $this->alimenterInfos('');
        unset($dao);
    }    

    protected function listeParametresRecherche($champ, $valeur, $valeur2 = '') {
        $rech = 'Evènements ';
        if ($champ == 'installation') {
            $rech .= 'de la centrale de ' . $valeur;
            $rech .= ' triés par date d\'incident'; //  (inversées)';
        }

        if ($champ == 'dateIncident') {
            $rech .= 'du ' . rezo::dateAMJversJMAstr($valeur);
            if ($valeur2 != '')
                $rech .= ' au ' . rezo::dateAMJversJMAstr($valeur2);
            $rech .= ' triés par centrale et par date';
        }
        return $rech;
    }

}

?>
