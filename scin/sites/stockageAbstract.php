<?php

/*
 * Tout ce qui concerne le stockage en SGBD
 */

abstract class StockageAbstract {

    protected $infosItem = array();

    // ================================================
    //    Ecriture CENTRALE dans base
    // ================================================
    public function StockerTousEvt1Centrale() {
        $incid = 0;

        foreach ($this->infosItem as $item) {
            $incid++;
            $incident = new Incident();
            IncidentMapper::map($incident, $item);
            $dao = new IncidentDao();
            $dao->save($incident);
            unset($incident);
            unset($dao);
        }
        return $incid;
    }

    // ================================================
    //    Ecriture DECISION dans base
    // ================================================
    public function StockerTousEvtDecision($viderTable = true) {
        // vider la table
        if ($viderTable) {
            echo html::parHtmlBal('vider table');
            $dao = new DecisionsASNDao();
            $nbr = $dao->truncateTable('decisionsASN');
            unset($dao);
        }

        // alimenter la table
        $decis = 0;

        foreach ($this->infosItem as $item) {
            $decis++;
            $decision = new DecisionsASN();
            DecisionsASNMapper::map($decision, $item);
            $dao = new DecisionsASNDao();
            $dao->save($decision);
            unset($decision);
            unset($dao);
        }
        return $decis;
    }

    // ================================================
    //    Mise à jour périodique CENTRALE dans base
    //    (date > dernière date evt)
    // ================================================
    public function miseAJourBaseIncidentEDF() {
// date AMJ de référence = dernière date d'incident stockée
        $dao = new IncidentDao();
        $dateRefAMJ = $dao->maxDateIncident();
// Récupérer les derniers évènements antérieurs à la date de référence
        $this->dernierEvtCentrales($dateRefAMJ);
        $bDemanteles = true;
        $this->dernierEvtCentrales($dateRefAMJ, $bDemanteles);
// tester la date d'incident
        //echo html::parHtmlBal('nombre incidents : ' . count($this->infosEvts));
        $incid = 0;
        $incidMaj = 0;
        unset($dao);

        foreach ($this->infosItem as $item) {
            $incid++;
            $dao = new IncidentDao();
// l'incident est-il déjà enregistré ?
            $incidentBase = $dao->daoRechIncidentSurDate($item['installation'], $item['dateIncident']);

            if ($incidentBase == null) {
                // Enregistrer l'incident  
                $incidMaj++;
                $incident = new Incident();
                IncidentMapper::map($incident, $item);
                $dao->save($incident);
                unset($incident);
                unset($dao);
                echo html::parHtmlBal('Incident à ' . $item['installation'] . ' le ' . $item['dateIncident']);
            }
            unset($dao);
        }
        if (count($this->infosItem) > 0) {
            echo html::parHtmlBal($incidMaj . ' incidents mis à jour sur ' . $incid . ' incidents trouvés.');
        } else {
            echo html::parHtmlBal('Pas de nouveaux incidents pour les centrales. La base est à jour.');
        }
    }

    // ================================================
    //    Mise à jour périodique INSTALLATION dans base
    //    (date > dernière date evt)
    // ================================================
    public function miseAJourBaseIncidentASN() {
        // Récupérer les derniers événements
        $b_UNE_SEULE_PAGE = true;
        $this->recupererPageListe($this->url, $b_UNE_SEULE_PAGE);
        //echo html::parHtmlBal('nombre incidents : ' . count($this->infosEvts));
        $incid = 0;
        $incidMaj = 0;

        foreach ($this->infosItem as $item) {
            $incid++;
            $dao = new IncidentDao();
            // l'incident est-il déjà enregistré ?
            $incidentBase = $dao->daoRechIncidentSurDate($item['installation'], $item['dateIncident'], 'ASN');

            if ($incidentBase == null) {
                // Enregistrer l'incident  
                $incidMaj++;
                $incident = new Incident();
                IncidentMapper::map($incident, $item);
                $dao->save($incident);
                unset($incident);
                echo html::parHtmlBal('Incident à ' . $item['installation'] . ' le ' . $item['dateIncident']);
            }
            unset($dao);
        }
        if (count($this->infosItem) > 0) {
            echo html::parHtmlBal($incidMaj . ' événements mis à jour sur ' . $incid . ' événements trouvés.');
        } else {
            echo html::parHtmlBal('Pas de nouveaux événements pour les installations. La base est à jour.');
        }
    }

    // ================================================
    //    Mise à jour périodique DECISION dans base
    //    (date > dernière date evt)
    // ================================================

    public function miseAJourBaseDecisionASN() {
// date AMJ de référence = dernière date de décision stockée
        /*  $dao = new DecisionsASNDao();
          $typeDateEstPublication = BaseDecision::DATEREF_EST_DATE_PUBLICATION;
          $dateRefAMJ = $dao->maxDateDecisionASN($typeDateEstPublication);
         */
// Récupérer les dernières décisions
        $b_UNE_SEULE_PAGE = true;
        $this->recupererPageListe($this->url, $b_UNE_SEULE_PAGE);
// tester la date d'incident
        //echo html::parHtmlBal('nombre incidents : ' . count($this->infosEvts));
        $incid = 0;
        $incidMaj = 0;

        foreach ($this->infosItem as $item) {
            $incid++;
            $dao = new DecisionsASNDao();
// Récupérer les dernières décisions antérieurs à la date de référence
// (inutile)
// l'incident est-il déjà enregistré ?
            $idASN = $item['titre'];

            /*  if ($typeDateEstPublication == BaseDecision::DATEREF_EST_DATE_PUBLICATION) {
              $date = $item['texteDetail'];
              } else {
              $date = $item['dateIncident'];
              }
             */
            $decisionBase = $dao->daoRechDecisionsASNSuridASN($idASN);

            if ($decisionBase == null) {
                // Enregistrer l'incident  
                $incidMaj++;
                $decision = new DecisionsASN();
                DecisionsASNMapper::map($decision, $item);
                $dao->save($decision);
                unset($decision);
                echo html::parHtmlBal('Décision ASN ' . $idASN); // . $item['installation'] . ' le ' . $date);
            }
            unset($dao);
        }
        if (count($this->infosItem) > 0) {
            echo html::parHtmlBal($incidMaj . ' décisions ASN  mises à jour sur ' . $incid . ' décisions trouvés.');
        } else {
            echo html::parHtmlBal('Pas de nouvelles décisions ASN. La base est à jour.');
        }
    }

    // ================================================
    //    Mise à jour périodique INSTALLATION dans base
    //    (date > dernière date evt)
    // ================================================
    public function miseAJourBaseArretsASN() {
// Récupérer les derniers événements
        $b_UNE_SEULE_PAGE = true;
        $this->recupererPageListe($this->url, $b_UNE_SEULE_PAGE);
        //echo html::parHtmlBal('nombre incidents : ' . count($this->infosEvts));
        $incid = 0;
        $incidMaj = 0;

        foreach ($this->infosItem as $item) {
            $incid++;
            $dao = new IncidentDao();
// l'incident est-il déjà enregistré ?
            $incidentBase = $dao->daoRechIncidentSurDate($item['installation'], $item['dateIncident'], 'ASN arret');

            if ($incidentBase == null) {
                // Enregistrer l'incident  
                $incidMaj++;
                $incident = new Incident();
                IncidentMapper::map($incident, $item);
                $dao->save($incident);
                unset($incident);
                echo html::parHtmlBal('Arrêt à ' . $item['installation'] . ' le ' . $item['dateIncident']);
            }
            unset($dao);
        }
        if (count($this->infosItem) > 0) {
            echo html::parHtmlBal($incidMaj . ' arrêts mis à jour sur ' . $incid . ' arrêts trouvés.');
        } else {
            echo html::parHtmlBal('Pas de nouveaux événements pour les installations. La base est à jour.');
        }
    }

// utilitaires    

    public function getInfosItem() {
        return $this->infosItem;
    }

}

?>
