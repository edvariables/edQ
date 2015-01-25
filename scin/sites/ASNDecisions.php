<?php

class ASNDecisions extends ASNabstract {

    protected $url = 'http://www.asn.fr/Reglementer/Bulletin-officiel-de-l-ASN/Decisions-de-l-ASN';

    function __construct($bRecupPage = true) {
        if ($bRecupPage)
            self::recupererPageListe($this->url);
    }

    /* ----------------------------------------- 
     * récupérer les infos répétitives 
     * spécifiques à la page
     * -----------------------------------------
     * 
     * Correspondance : (Incident = objet standard)

      Incidents               decisionsASN
      ---------               ------------
      id                      id
      installation            installation
      gravité
      titre                   *** idASN ***
      dateIncident            *** dateDecision
      texte                   texte
      texteDetail             *** datePublication ***
      fichierTelecharge       fichierTelecharge
      url                     url
      page
      dateCapture             dateCapture
      origine                 (toujours ASN)

     *  */

    protected function alimenterInfos($url) {
        $item = array();

// le premier ul[class=news_list2], de rang=0, est vide
        foreach ($this->html->find('div[class=view resume]') as $ligneInfo) {
            $texte = self::textSansToolTips($ligneInfo); // le texte contient des Tipstools à virer
            $item['texte'] = trim($texte);
            
            $item['installation'] = '';
            $installation = self::trouveInstallDansLibelle($texte);
            if ($installation != '') {
                $item['installation'] = $installation;
            }

// installation et titre  
            $libelleInstallation = trim($ligneInfo->find('h3 > a', 0)->plaintext);
            $item['libelleInstallation'] = $libelleInstallation;

            $item['titre'] = $this->recupererDecision($libelleInstallation);

            $dateDecisionAMJ = $this->trouveDateDecision(trim($libelleInstallation));
            $item['dateIncident'] = $dateDecisionAMJ;
            $publieLe = $ligneInfo->find('p[class=meta]', 0)->plaintext;  // spécifique
            $datePublicationAMJ = $this->trouveDatePublication(trim($publieLe));
            $item['texteDetail'] = $datePublicationAMJ; //'';
//
            // on récupère le lien en ajoutant le domaine
            $lienUrl = $ligneInfo->find('h3 > a', 0)->outertext;
            $lienUrl = self::ajoutDomaineALUrl(trim($lienUrl));

// le détail est sur une autre page, il faut suivre l'url et la traiter            
// $pageDetail = parent::liensPageDetails($lienUrl);  // ça marche         
// $pageDetail = self::liensPageDetails($lienUrl);   // ça marche 
            $pageDetail = $this->liensPageDetails($lienUrl);
            if ($pageDetail == self::PAGE_DET_NON_TRV) {
                $pageDetail .= ' ' . html::urlDunLien($lienUrl);
            }
            $item['fichierTelecharge'] = $lienUrl . html::B_BR . $pageDetail;
            $item = $this->alimenterFinInfos($item, $url);
// on stocke les infos
            $this->infosItem[] = $item;
            
            //break;//DEBUG
        }
    }

    /*     * *****************************************************
     * Recupérer la page demandee
     * cf ASNSuiteDinspection
     * ******************************************************** */

    protected function recupererPageListe($url) {
        $this->html = file_get_html($url);

        if ($this->html == false) { // Non trouvée
            $this->alimenterErrNonTrouve($url);
//            
        } else { // on traite la page         
            $this->alimenterInfos($url);
//echo html::parHtmlBal('première page ');
            $bTOUTES_LES_PAGES = TRUE;
            $bDEBOGAGE = false;

            if ($bTOUTES_LES_PAGES) {
                $numPage = 2;
// 'http://www.asn.fr/index.php/content/view/full/31893//%28offset%29/20'
// ça démarre à 15 avec un incrément de 15
                $urlSuite = $this->trouverUrlPageSuivante($numPage);
                $posFin = strlen($urlSuite) - 2;
                $debutUrl = substr($urlSuite, 0, $posFin);
                $finUrl = 15;

                $totalPages = 1;//ED141106 4; // 11 en local. Spécifique pour Décisions.

                for ($numPage = 2; $numPage <= $totalPages; $numPage++) {
                    $finUrl += 15;
                    $urlSuite = $debutUrl . $finUrl;
                    $this->html = file_get_html($urlSuite);
                    if ($this->html != false) {
//echo html::parHtmlBal('appel alimenterInfos');
                        $this->alimenterInfos($urlSuite);
                    }
                    if ($bDEBOGAGE) {
                        echo html::parHtmlBal($urlSuite);
                    }
                }
            }
        } // if 1
    }

    /*     * *****************************************************
     * Recupérer une tranche de pages basée sur l'offset (incr de 20)
     * (permet de découper la récup et de ne pas saturer le serveur)
     * *********************************************************** */

    public function recupererTranche($offset) {
        $finUrl = $offset;
        $totalPages = 4; // maxi pour serveur.
        $debutUrl = 'http://www.asn.fr/index.php/content/view/full/31893//%28offset%29/';

        for ($numPage = 1; $numPage <= $totalPages; $numPage++) {
            $finUrl += 20;
            $urlSuite = $debutUrl . $finUrl;
            $this->html = file_get_html($urlSuite);
            if ($this->html != false) {
                $this->alimenterInfos($urlSuite);
            }
        }
    }

    /* ----------------------------------------- 
     *  Affichage (surcharge)
     * ----------------------------------------- */

    public function afficherListeDiverses($afficherDetail = self::DETAIL_NEPAS_AFFICHER) {
        $bDEBOGUAGE = true;
        $bPremierPassage = true;

        foreach ($this->infosItem as $item) {
            if ($bPremierPassage) {
// trier le tableau par date de publication
//  $this->infosItem['texteDetail']
//echo Html::parHtmlBal($item['url']);
                $this->afficherListeDiverses1P($item['url']);
                $bPremierPassage = false;
            }

            if ($item['libelleInstallation'] != '') {
                echo Html::parHtmlBal($item['libelleInstallation'], 'h2');
            } else {
                if (array_key_exists('installation', $item)) {
                    echo Html::parHtmlBal($item['installation'], 'h2');
                }
            }

            if ($item['dateIncident'] != '') {
                $date = rezo::dateAMJversJMAstr($item['dateIncident']);
                $libelleH3 = $date . ' - ';
            } else {
                $libelleH3 = '';
            }
            $libelleH3 .= $item['titre'];

            if (array_key_exists('id', $item)) {
                $libelleH3 .= ' (id scin = ' . $item['id'] . ')';
            }
            if (array_key_exists('origine', $item)) {
                $libelleH3 .= ' - ' . $item['origine'];
            }
            echo Html::parHtmlBal($libelleH3, 'h3');
//var_dump($item['dateIncident']);

            if ($bDEBOGUAGE) {
                $dates = 'Date de la décision : ' . $item['dateIncident'];
                $dates .= ' - Date de publication : ' . $item['texteDetail'];
                echo Html::parHtmlBal($dates, 'div');
            }

            if (array_key_exists('installation', $item)) {
                if ($item['installation'] != '') {
                    echo Html::parHtmlBal($item['installation'], 'h4');
                }
            }

            echo Html::parHtmlBal($item['texte'], 'div');

            if (array_key_exists('fichierTelecharge', $item)) {
                if ($item['fichierTelecharge'] != '') {
                    echo Html::parHtmlBal($item['fichierTelecharge'], 'p');
                }
            }
        }
    }

    /* ----------------------------------------- 
     * trois formes de code décision à isoler :
     * 1) = CODEP-CLG-2013-017474 
     * 2) = n° 2013-DC-0341 (ou no avec ou sans espace)
     * 3) = Décision CLG-2013-051991 du (oubli du CODEP)
     * ----------------------------------------- */

    const RECH_CODEP = 'CODEP';
    const RECH_NO = 'n°';
    const RECH_NO2 = 'no';
    const RECH_NO3 = 'N°';
    const RECH_D = 'd';
    const RECH_CLG = 'CLG';

    private function recupererDecision($libelleInstallation) {
        $decision = '';
        $position = strpos($libelleInstallation, self::RECH_CODEP);
        if ($position !== false) {
// cas 1 : Codep : on traite 
            $position2 = strpos($libelleInstallation, self::RECH_D, $position);
            $longueur = $position2 - $position;
            $decision = trim(substr($libelleInstallation, $position, $longueur));
//            
        } else {
// cas 2 : n°  
            $position = strpos($libelleInstallation, self::RECH_NO);
            if ($position == false) {  // deuxième chance       
                $position = strpos($libelleInstallation, self::RECH_NO2);
                if ($position == false) {  // troisième chance       
                    $position = strpos($libelleInstallation, self::RECH_NO3);
                }
            }
            if ($position !== false) {
// cas 2 : on traite 
                $position2 = strpos($libelleInstallation, self::RECH_D, $position);
                $longueur = $position2 - $position - 3;
                $depart = $position + 3;
                $decision = trim(substr($libelleInstallation, $depart, $longueur));
            } else {
// cas 3 : CLG  
                $position = strpos($libelleInstallation, self::RECH_CLG);
                if ($position !== false) {
// cas 3 : on traite  
                    $position2 = strpos($libelleInstallation, self::RECH_D, $position);
                    $longueur = $position2 - $position;
                    $decision = 'CODEP-' . trim(substr($libelleInstallation, $position, $longueur));
                } else {
// erreur…                                
                }
            }
        }
//echo html::parHtmlBal($decision);
        return $decision;
    }

    /* ----------------------------------------- 
     * 
     * ----------------------------------------- */

    private function trouveDateDecision($libelle) {
        $position = strrpos($libelle, 'du '); // dernier 'du '
        if ($position !== false) {
            $dateBrute = substr($libelle, ($position + 3));
//echo html::parHtmlBal('$date decision Brute : ' . $dateBrute);
            $dateAMJ = rezo::dateMoisEnLettreversJMA($dateBrute);
            return $dateAMJ;
        }
    }

}

?>
