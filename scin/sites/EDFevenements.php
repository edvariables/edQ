<?php

/**
 * Récupère les évênements sue le site d'EDF energie.edf.com/
 *
 * @author jm
 */
class EDFevenements extends StockageAbstract {

    const URL_FNC = 'http://energie.edf.com/nucleaire/carte-des-centrales-nucleaires/evenements-';
    const URL_DEM1 = 'http://energie.edf.com/nucleaire/deconstruction/carte-des-centrales-en-deconstruction/';
    const URL_DEM2 = '/evenements-';
    const URL_FIN = '.html';
    const INES = 'Echelle INES Niveau ';
    const INES2 = 'inesLevel il';

    private $listeCentrale = array();
    private $listeDemanteles = array();

    /*     * ****************************************************
     *  contructeur :  initialisations des listes de centrales
     * ******************************************************** */

    public function __construct() {
        $this->getListeCentralesEnFonction();
        $this->getListeCentralesDemanteles();
    }

    /*     * *****************************************************
     * Lecture des pages et récupération des infos en tableaux
     * ******************************************************** */

    public function dernierEvtCentrales($dateRef, $bDemanteles = false) {
        $bAtraiter = false;

        if ($dateRef != '') {
            //$dateNouveaute = new DateTime(trim($dateRef)); // '2013-05-31'
            $dateNouveaute = $dateRef;
        } else {
            $bAtraiter = true;
        }

        if ($bDemanteles) {
            $listeCtrls = $this->listeDemanteles;
        } else {
            $listeCtrls = $this->listeCentrale;
        }

        foreach ($listeCtrls as $centrale => $tbnumpage) {

            // constituer l'url            
            if ($bDemanteles) {
                $numpage = $tbnumpage[0];
                $nomCentrale = $tbnumpage[1];
                $url = $this::URL_DEM1 . $nomCentrale . $this::URL_DEM2;
                $url .= $numpage . $this::URL_FIN;
            } else {
                $url = $this::URL_FNC . $tbnumpage . $this::URL_FIN;
            }
            // on récupère la page
            $html = file_get_html($url);

            if ($html == false) { // Non trouvée
                $this->alimenterErrNonTrouve($centrale, $url);
                //               
            } else { // on traite la page
                //               
                if ($dateRef != '') {  // Selection sur la date
                    $chaineDateEvt = trim($html->find('h3', 0)->find('span[class=date]', 0)->plaintext);
                    //$dateEvenement = new DateTime(trim(str_replace('/', '-', $chaineDateEvt)));
                    $dateEvenement = rezo::dateJMAversAMJstr(trim($chaineDateEvt));
                    //echo html::parHtmlBal($centrale . ' : ' . $dateEvenement . ' doit > ' . $dateNouveaute);
                    $bAtraiter = ($dateEvenement > $dateNouveaute);
                }

                if ($bAtraiter) {
                    $this->alimenterInfosEvts($centrale, $html, $url, $bDemanteles);
                }
                $html->clear();
                unset($html);
            }
        } // foreach
        return true;
    }

    /*     * *****************************************************
     *   Récupération des infos dans le DOM
     * 
     * (voir le document infos/hierarchie.txt 
     * pour les détails de l'accès au dom)
     * ******************************************************** */

    private function alimenterInfosEvts($centrale, $html, $url, $bDemanteles = false) {
        $item = array();
        $item['installation'] = $centrale;
        $article = $html->find('div[class=contentAcc]', 0)->find('h3', 0);

        // sous-ensembles du <h3> : titre, date (puis gravité)
        $item['titre'] = trim($article->find('a[href=#]', 0)->plaintext);
        $item['gravite'] = $this->alimenterInfosEvtsGravite($article);
        $date = $article->find('span[class=date]', 0)->plaintext;
        $date = rezo::dateJMAversAMJstr(trim($date));
        $item['dateIncident'] = $date;
        // texte de l'evt
        $article = $html->find('div[class=contentAcc]', 0)->find('div[class=contentText]', 0);
        $item['texte'] = trim($article->plaintext);

        if ($bDemanteles) {
            $item['page'] = $this->listeDemanteles[$centrale];
        } else {
            $item['page'] = $this->listeCentrale[$centrale];
        }
        $item = $this->alimenterFinInfos($item, $url);
        // on stocke les infos récupérées
        $this->infosItem[] = $item;
    }

    //-------------------------------------------------
    //  stocker les infos Tous evts d'1 centrale 
    //-------------------------------------------------

    const RECH_IDH3 = 'id="Acc';

    private function alimenterInfosTousEvts($centrale, $html, $url, $bDemanteles = false) {
        // récupérer au préalable les textes dans un tableau
        foreach ($html->find('div[class=contentAcc]', 0)->find('div[class=contentText]') as $article) {
            $existePDF = $article->find('a', 0);

            if ($existePDF != null) {
                $lienPDF = $existePDF->outertext;
                $lienPDF = self::ajoutDomaineALUrl(trim($lienPDF));
                $itemTexte[] = trim($lienPDF);
            } else {
                $itemTexte[] = trim($article->plaintext);
            }
            //$itemTexte[] = trim($article->plaintext);
        }
        $i = 0;
        foreach ($html->find('div[class=contentAcc]', 0)->find('h3') as $article) {
            $chaineRech = $article->outertext;
            $pos = strpos($chaineRech, self::RECH_IDH3);  // verifier qu'il s'agit d'un bon H3
            if ($pos !== false) {
                if ($i == 0) {
                    $item['installation'] = $centrale;

                    if ($bDemanteles) {
                        $item['page'] = $this->listeDemanteles[$centrale];
                    } else {
                        $item['page'] = $this->listeCentrale[$centrale];
                    }
                }
                // sous-ensembles du <h3> : titre, date (puis gravité) et texte
                $item['gravite'] = $this->alimenterInfosEvtsGravite($article);
                $item['titre'] = trim($article->find('a[href=#]', 0)->plaintext);
                $date = $article->find('span[class=date]', 0)->plaintext;
                $date = rezo::dateJMAversAMJstr(trim($date));
                $item['dateIncident'] = $date;
                $item['texte'] = $itemTexte[$i];

                $item = $this->alimenterFinInfos($item, $url);
                // on stocke les infos récupérées
                $this->infosItem[] = $item;
                $i++;
            } // ($pos !== false)
        }
    }

    /*     * *****************************************************
     *   Récupération des infos dans le DOM : gravité
     * ******************************************************** */

    private function alimenterInfosEvtsGravite($article) {

        //$rech = 'span[title="' . $this::INES . '"]';
        $rech = 'span[class="' . $this::INES2 . '"]';
        $grav = trim($article->find($rech, 0)->plaintext);

        if ($grav != null) {
            return $grav;
        } else {
            // INEX est une échelle de 7 échelons
            for ($n = 0; $n < 8; $n++) {
                //$rech = 'span[title="' . $this::INES . $n . '"]';
                $rech = 'span[class="' . $this::INES2 . $n . '"]';
                $grav = trim($article->find($rech, 0)->plaintext);

                if ($grav != null) {
                    return $grav;
                    break; // on sort dès que trouvé
                }
            }
            return 0;
        }
    }

    /*     * *****************************************************
     * Afficher l'info page non trouvée dans le même moule
     * ******************************************************** */

    private function alimenterErrNonTrouve($centrale, $url) {
        //echo $url . ' - page non trouvee !';
        $item = array();
        $item['installation'] = $centrale;
        $item['gravite'] = '';
        $item['titre'] = 'Page non trouvee : ' . $url;
        $item['dateIncident'] = '';
        $item['texte'] = '';
        $item['url'] = $url;
        $item['page'] = '';
        $item['dateCapture'] = '';

        // on stocke les infos
        $this->infosItem[] = $item;
    }

    // =======================================================
    //  Récupération des infos : tous les evts d'une centrale
    // =======================================================

    public function tousEvtsUneCentrale($centrale, $bDemanteles = false) {

        if ($bDemanteles) {
            $tbnumpage = $this->listeDemanteles[$centrale];
        } else {
            $tbnumpage = $this->listeCentrale[$centrale];
        }
        // constituer l'url            
        if ($bDemanteles) {
            $numpage = $tbnumpage[0];
            $nomCentrale = $tbnumpage[1];
            $url = $this::URL_DEM1 . $nomCentrale . $this::URL_DEM2;
            $url .= $numpage . $this::URL_FIN;
        } else {
            $url = $this::URL_FNC . $tbnumpage . $this::URL_FIN;
        }
        // on récupère la page
        $html = file_get_html($url);

        if ($html == false) { // Non trouvée
            $this->alimenterErrNonTrouve($centrale, $url);
            //               
        } else { // on traite la page
            $this->alimenterInfosTousEvts($centrale, $html, $url, $bDemanteles);

            $bTOUTES_LES_PAGES = TRUE;
            $bDEBOGAGE = false;

            if ($bTOUTES_LES_PAGES) {
                $numPage = 2;
                $totalPages = $this->trouverTotalPages($html);
                //$totalPages = 5;

                if ($bDEBOGAGE) {
                    echo html::parHtmlBal('pages=' . $totalPages);
                    //break;
                }

                for ($numPage = 2; $numPage <= $totalPages; $numPage++) {
                    $urlSuite = $url . '&page=' . $numPage;
                    $html = file_get_html($urlSuite);
                    if ($html != false) {
                        $this->alimenterInfosTousEvts($centrale, $html, $urlSuite, $bDemanteles);
                    }
                }
            }
        }
    }

    protected function alimenterFinInfos($item, $url) {
        $item['libelleInstallation'] = '';
        $item['texteDetail'] = '';
        $item['url'] = $url;
        $item['dateCapture'] = date('Y/m/j'); // date du jour
        $item['origine'] = 'EDF';
        return $item;
    }

    //-------------------------------------------------
    //  déterminer le nombre de pages  
    //-------------------------------------------------
    protected function trouverTotalPages($html) {
        $page = 0;
        foreach ($html->find('div[id=ContentPagination]', 0)->find('a') as $article) {
            if (substr($article->plaintext, 0, 4) != 'Page') {
                $page = $article->plaintext;
            }
        }
        return $page;
    }

    /*     * *****************************************************
     * Afficher les infos récupérées dans la page html
     * ******************************************************** */

    public function afficherEvenements() {
        $bPremierPassage = true;

        foreach ($this->infosItem as $item) {

            if ($bPremierPassage) {
                $url = html::lienHtml(trim($item['url'])); // on rend le lien cliquable
                $info = 'Nombre d\'informations dans la liste : ' . count($this->infosItem);
                echo Html::parHtmlBal((Html::B_SMALL . $info . Html::B_SMALLF), 'div');
                $bPremierPassage = false;
            }

            $gravite = (string) $item['gravite'];
            if ($gravite == '') {
                $gravite = '0';
            }
            echo Html::parHtmlBal($item['installation'], 'h2');
            $date = rezo::dateAMJversJMAstr($item['dateIncident']);
            $h3 = $date . ' - ' . $item['titre'] . ' - Gravité : ' . $gravite;
            if ($item['origine'] == 'ASN arret')
                $classe = 'arret';
            else
                $classe = $item['origine'];
            echo Html::parHtmlBal($h3, 'h3', $classe);
            echo Html::parHtmlBal($item['texte'], 'div');
            echo Html::parHtmlBal('');

            $lienUrl = Html::lienHtml($item['url']);
            $petitLien = Html::parHtmlBal($lienUrl, 'small'); // en petit
            echo Html::parHtmlBal($petitLien, 'div');
        }
    }

    /*     * *****************************************************
     * Afficher les infos récupérées dans la page html
     * ******************************************************** */

    public function afficherTousEvt1Centrale() {
        $bPremierPassage = true;

        foreach ($this->infosItem as $item) {
            if ($bPremierPassage) {
                echo Html::parHtmlBal('');
                $url = html::lienHtml(trim($item['url'])); // on rend le lien cliquable
                $info = 'Nombre d\'informations dans la liste : ' . count($this->infosItem);
                $info .= '  -  Source : ' . $url;
                echo Html::parHtmlBal((Html::B_SMALL . $info . Html::B_SMALLF), 'div');
                echo Html::parHtmlBal('');
                echo Html::parHtmlBal($item['installation'], 'h2');
                $bPremierPassage = false;
            }
            $gravite = (string) $item['gravite'];
            if ($gravite == '') {
                $gravite = '0';
            }
            if (simple_html_dom_node::is_utf8($item['dateIncident'])) {
                
            }
            $date = rezo::dateAMJversJMAstr($item['dateIncident']);
            $h3 = $date . ' - ' . $item['titre'] . ' - Gravité : ' . $gravite;
            if ($item['origine'] == 'ASN arret')
                $classe = 'arret';
            else
                $classe = $item['origine'];
            echo Html::parHtmlBal($h3, 'h3', $classe);
            echo Html::parHtmlBal($item['texte'], 'div');
        }

        echo Html::parHtmlBal('');
    }

    /*     * ****************************************
     *   Ajouter le nom de domaine dans une url
     * ******************************************* */

    const DOMAINE_ASN = 'http://energie.edf.com';
    const HREF_RECH = 'href="';

    protected function ajoutDomaineALUrl($url) {
        $href_new = self::HREF_RECH . self::DOMAINE_ASN;
        $url = str_replace(self::HREF_RECH, $href_new, $url);
        return $url;
    }

    // ================================================
    //    fonctions utilitaires
    // ================================================
    private function getListeCentralesEnFonction() {
        $this->listeCentrale['Belleville-sur-Loire'] = '45855';
        $this->listeCentrale['Blayais'] = '45862';
        $this->listeCentrale['Bugey'] = '45869';
        $this->listeCentrale['Cattenom'] = '45876';
        $this->listeCentrale['Chinon'] = '45925';
        $this->listeCentrale['Chooz'] = '45926';
        $this->listeCentrale['Civaux'] = '45916';
        $this->listeCentrale['Cruas-Meysse'] = '45910';
        $this->listeCentrale['Dampierre-en-Burly'] = '45889';
        $this->listeCentrale['Fessenheim'] = '45896';  //'45876';
        $this->listeCentrale['Flamanville'] = '45742';
        //$this->listeCentrale['Flamanville 3 - EPR'] = '53185'; // actus et non evt
        $this->listeCentrale['Golfech'] = '45904';
        $this->listeCentrale['Gravelines'] = '45959';
        $this->listeCentrale['Nogent-sur-Seine'] = '45961';
        $this->listeCentrale['Paluel'] = '45964';
        $this->listeCentrale['Penly'] = '45965';
        $this->listeCentrale['Saint-Alban'] = '45966';
        $this->listeCentrale['Saint-Laurent-des-Eaux'] = '45967';
        $this->listeCentrale['Tricastin'] = '45968';
    }

    private function getListeCentralesDemanteles() {
        $this->listeDemanteles['Brennilis'] = array('48060', 'centrale-nucleaire-de-brennilis');
        $this->listeDemanteles['Creys-Malville'] = array('48290', 'centrale-de-creys-malville');
    }

    public function getListeToutesCentrales() {
        $toutesCentrales = array();

        foreach ($this->listeCentrale as $centrale => $page) {
            $toutesCentrales[] = $centrale;
        }
        $toutesCentrales[] = '--------------------'; // séparateur avant centrales en démantèlement
        foreach ($this->listeDemanteles as $centrale => $page) {
            $toutesCentrales[] = $centrale;
        }
        return $toutesCentrales;
    }

    public function getListeCentrale() {
        return $this->listeCentrale;
    }

    public function getListeDemanteles() {
        return $this->listeDemanteles;
    }

    /* public function getInfosEvts() {
      return $this->infosItem;
      } */
}

?>
