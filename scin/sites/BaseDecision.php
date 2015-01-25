<?php
/*
 * Affichage depuis la base de données (table decision)
 */
class BaseDecision extends BaseAbstract {

    const DATEREF_EST_DATE_DECISION = 0;
    const DATEREF_EST_DATE_PUBLICATION = 1;

    function __construct() {
        //echo html::parHtmlBal('*** nouvelle decision base');
    }

// 
    protected function alimenterInfos($url) {
        $i = 0;

        foreach ($this->listeObjets as $decision) { //  $id => 
            $i++;
            //echo html::parHtmlBal($i);
            $item = DecisionsASNMapper::mapToArray($decision);
            $this->infosItem[] = $item;
        }
    }

    public function decisionsDUneInstallation($installation) {
        $dao = new DecisionsASNDao();
        $this->listeObjets = $dao->daoFind(DecisionsASNDao::TRI_PAR_DATEPUBLICATION, 'installation', $installation);
        $this->paramsRecherche = $this->listeParametresRecherche('installation', $installation);
        $this->alimenterInfos('');
        unset($dao);
    }

    public function decisionsAUneDate($deDate, $aDate = '') {
        //echo html::parHtmlBal('de '.$deDate.' à '.$aDate);
        $dao = new DecisionsASNDao();
        if (TRUE) {
            // date de publication puis date de décision
            $this->listeObjets = $dao->daoFind(DecisionsASNDao::TRI_PAR_DATEPUBLICATION_ET_DATEDECISION, 'datePublication', $deDate, $aDate);
            $this->paramsRecherche = $this->listeParametresRecherche('datePublication', $deDate, $aDate);
        } else {
            // date decision
            $this->listeObjets = $dao->daoFind(DecisionsASNDao::TRI_PAR_DATEDECISION, 'dateDecision', $deDate, $aDate);
            $this->paramsRecherche = $this->listeParametresRecherche('dateDecision', $deDate, $aDate);
        }
        if (FALSE) {
            // date de publication            
            $this->listeObjets = $dao->daoFind(DecisionsASNDao::TRI_PAR_DATEPUBLICATION, 'datePublication', $deDate, $aDate);
            $this->paramsRecherche = $this->listeParametresRecherche('datePublication', $deDate, $aDate);
        }
        if (FALSE) {
            // tri pzr installation : impossible, trop peu d'installations alimentées
            $this->listeObjets = $dao->daoFind(DecisionsASNDao::TRI_PAR_INSTALLATION_ET_DATEPUBLICATION_DESC, 'datePublication', $deDate, $aDate);
            $this->paramsRecherche = $this->listeParametresRecherche('datePublication', $deDate, $aDate);
        }
        $this->alimenterInfos('');
        unset($dao);
    }

    protected function listeParametresRecherche($champ, $valeur, $valeur2 = '') {
        $rech = 'Decisions ASN ';
        if ($champ == 'installation') {
            $rech .= 'concernant l\'installation ' . $valeur;
            $rech .= ' triées par dates de publication'; // (inversées)';
        }

        if ($champ == 'datePublication') {
            if (TRUE) {
                $rech .= 'du ' . rezo::dateAMJversJMAstr($valeur);
                if ($valeur2 != '')
                    $rech .= ' au ' . rezo::dateAMJversJMAstr($valeur2);
                $rech .= ' triées par date de publication';
                
            } else {
                $rech .= 'du ' . rezo::dateAMJversJMAstr($valeur);
                if ($valeur2 != '')
                    $rech .= ' au ' . rezo::dateAMJversJMAstr($valeur2);
                $rech .= ' triées par installation et par date de publication';
            }
        }

        if ($champ == 'dateDecision') {
            $rech .= 'du ' . rezo::dateAMJversJMAstr($valeur);
            if ($valeur2 != '')
                $rech .= ' au ' . rezo::dateAMJversJMAstr($valeur2);
            $rech .= ' triées par date de décision';
        }
        return $rech;
    }

}

?>
