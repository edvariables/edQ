<?php

/**
 * arrêts de réacteurs
 * 
 *  Site de l'ASN
 * 
 * L'essentiel des traitements est commun (donc dans la classe abstraite),
 * la récupération des infos est spécifique à chaque type de page.
 * 
 * ->plaintext renvoi du texte pur
 * ->outertext conserve toutes les balises html
 *
 * @author jm
 */
//===========================================
//     Fonctions communes génériques
//===========================================
abstract class ASNabstract extends StockageAbstract {

    protected $html;

    abstract protected function alimenterInfos($url);

    /*     * *****************************************************
     * Recupérer la page demandee
     * ******************************************************** */

    protected function recupererPageListe($url, $b_UNE_SEULE_PAGE = false) {
        $this->html = file_get_html($url);

        if ($this->html == false) { // Non trouvée
            $this->alimenterErrNonTrouve($url);
//            
        } else { // on traite la page         
            $this->alimenterInfos($url);
            //echo html::parHtmlBal('première page ');
            if (!$b_UNE_SEULE_PAGE) {
                $bTOUTES_LES_PAGES = TRUE;
                $bDEBOGAGE = false;

                if ($bTOUTES_LES_PAGES) {
                    $numPage = 2;
                    $totalPages = $this->trouverTotalPages($this->html);

                    if ($bDEBOGAGE) {
                        echo html::parHtmlBal('appel trouverTotalPages');
                        echo html::parHtmlBal('total pages ' . $totalPages);
                        //break;
                    }

                    for ($numPage = 2; $numPage <= $totalPages; $numPage++) {
                        $urlSuite = $this->trouverUrlPageSuivante($numPage);
                        $this->html = file_get_html($urlSuite);
                        if ($this->html != false) {
                            //echo html::parHtmlBal('appel alimenterInfos');
                            $this->alimenterInfos($urlSuite);
                        }
                        if ($bDEBOGAGE) {
                            echo html::parHtmlBal($urlSuite);
                        }
                    }
                    $this->html->clear();
                    unset($this->html);
                }
            }  //  !$b_UNE_SEULE_PAGE
        }  //  if 1
    }

    /*     * *****************************************************
     * Afficher l'info page non trouvée dans le même moule
     * ******************************************************** */

    protected function alimenterErrNonTrouve($url, $installation = '') {
        //echo $url . ' - page non trouvee !';
        $item = array();
        $item['titre'] = 'Page non trouvee : ' . $url;
        $item['libelleInstallation'] = $installation;
        //$item['installation'] = '';
        $item['dateIncident'] = '';
        $item['texte'] = 'Vérifier l\'url qui a peut-être changé';
        $item['texteDetail'] = '';
        //$item['fichierTelecharge'] = '';
        $item['url'] = $url;
        $item['page'] = '';
        $item['dateCapture'] = '';

        // on stocke les infos
        $this->infosItem[] = $item;
    }

    /*     * *****************************************************
     * Afficher les infos récupérées dans la page html
     * ******************************************************** */

    const DETAIL_AFFICHER = 1;
    const DETAIL_NEPAS_AFFICHER = 0;

    public function afficherListeDiverses($afficherDetail = self::DETAIL_NEPAS_AFFICHER) {
        $bPremierPassage = true;

        foreach ($this->infosItem as $item) {
            if ($bPremierPassage) {
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

            $date = $item['dateIncident'];

            if ($date != '') {
                $date = rezo::dateAMJversJMAstr($date);
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

            if (array_key_exists('installation', $item)) {
                if ($item['installation'] != '') {
                    echo Html::parHtmlBal($item['installation'], 'h4');
                }
            }

            if ($afficherDetail == self::DETAIL_AFFICHER) {
                echo Html::parHtmlBal(Html::parHtmlBal($item['texte'], 'em'), 'div'); // en italique
                echo Html::parHtmlBal('');
                echo Html::parHtmlBal($item['texteDetail'], 'div');
            } else {
                echo Html::parHtmlBal($item['texte'], 'div');
            }

            if (array_key_exists('fichierTelecharge', $item)) {
                if ($item['fichierTelecharge'] != '') {
                    echo Html::parHtmlBal($item['fichierTelecharge'], 'p');
                }
            }
            flush();
        }
    }

    public function afficherListeDiverses1P($url) {
        $url = html::lienHtml(trim($url)); // on rend le lien cliquable
        $info = 'Consulté le : ' . date('d/m/Y');
        $info .= ' - Nombre d\'informations dans la liste : ' . count($this->infosItem);
        $info .= '  -  Source : ' . $url;
        echo Html::parHtmlBal((Html::B_SMALL . $info . Html::B_SMALLF), 'div');
    }

    /*     * *****************************************************
     * Afficher les infos récupérées dans la page html
     * pour une installation
     * ******************************************************** */

    const TEXTE_SYNTHESE = 0;
    const TEXTE_DETAIL = 1;
    const TEXTE_DETAIL_ET_SYNTHESE = 2; // plus tard

    public function afficherIncidents1Installation($detail = self::TEXTE_SYNTHESE) {
        //echo 'afficherIncidents1Installation';
        $bPremierPassage = true;

        foreach ($this->infosItem as $item) {
            if ($bPremierPassage) {
                echo Html::parHtmlBal('');
                echo Html::parHtmlBal($item['libelleInstallation'], 'h2');
                echo Html::parHtmlBal((Html::B_SMALL . $item['url'] . Html::B_SMALLF), 'div');
                $bPremierPassage = false;
            }
            $date = rezo::dateAMJversJMAstr($item['dateIncident']);
            echo Html::parHtmlBal($date . ' - ' . $item['titre'], 'h3');

            switch ($detail) {
                case self::TEXTE_SYNTHESE:
                    $texte = $item['texte'];
                    break;
                case self::TEXTE_DETAIL:
                    $texte = $item['texteDetail'];
                    break;
                default:
                    $texte = '';
                    break;
            }
            echo Html::B_DIV . $texte . Html::B_DIVf;
        }

        echo Html::B_BR;
    }

    /*     * ****************************************
     *   Ajouter le nom de domaine dans une url
     * ******************************************* */

    const DOMAINE_ASN = 'http://www.asn.fr';
    const HREF_RECH = 'href="';

    protected function ajoutDomaineALUrl($url) {
        if($url[0] === '/')
            return self::DOMAINE_ASN . $url;
        $href_new = self::HREF_RECH . self::DOMAINE_ASN;
        $url = str_replace(self::HREF_RECH, $href_new, $url);
        return $url;
    }

    // ------------------------------------------
    //   récupérer le texte de la page détail
    // ------------------------------------------

    const PAGE_DET_NON_TRV = 'Page détail non trouvée.';
    const PARAGRAPHE_INUTILE_1 = 'Pour en savoir plus :';
    const PARAGRAPHE_INUTILE_2 = 'Échelle INES pour le classement des incidents et accidents nucléaires';
    const PARAGRAPHE_INUTILE_3 = '(format PDF - 300,76 ko)';
    const PAR_INU1 = 'ko';
    const PAR_INU2 = '- 300,76';
    const PAR_INU3 = 'PDF';
    const PAR_INU4 = '(format';
    const PAR_INU5 = ' )';

    public function traiterPageDetail($lienUrl) {
        $urlseul = html::urlDunLien($lienUrl);
        $pageDetail = file_get_html($urlseul);

        if ($pageDetail == false) { // Non trouvée
            echo "<br>Rien à l'adresse $urlseul";
            return self::PAGE_DET_NON_TRV;
        } else { // on traite la page   
            // récupérer les paragraphes en faisant un peu de ménage
            $texte = '';

            if (TRUE) {
                // balade dans le DOM en "aveugle" = sans connaitre le tag
                $atraiter = $pageDetail->find('div[class="block editorial"]', 0);

                if ($atraiter === false) { // Non trouvée
                    return '';
                } else { // on traite
                    // on parcours tous les éléments children (au + 100)
                    for ($i = 0; $i < 100; $i++) {
                        $paragraphe = $atraiter->childNodes($i);
                        if ($paragraphe != NULL) {
                            $texte .= $this->textSansToolTips($paragraphe);
                            //echo Html::parHtmlBal($paragraphe->tag);
                        } else {
                            break;
                        }
                    }
                }
            } else {
                // alternative : seuls les <p>
                $aEnlever = $pageDetail->find('div[class=block1]')->outertext; // inner ? // inutilisé
                foreach ($pageDetail->find('p') as $paragraphe) { // 'p', 'ol', 'ul'
                    $texte .= self::textSansToolTips($paragraphe);
                }
            }
        }
        // nettoyage
        $pageDetail->clear();
        unset($pageDetail);
        $tabulation = chr(9);
        $texte = str_replace($tabulation, '', $texte);
        $texte = str_replace(self::PARAGRAPHE_INUTILE_1, '', $texte);
        $texte = str_replace(self::PARAGRAPHE_INUTILE_2, '', $texte);
        //$texte = str_replace(self::PARAGRAPHE_INUTILE_3, '', $texte);
        $texte = str_replace(self::PAR_INU1, '', $texte);
        $texte = str_replace(self::PAR_INU2, '', $texte);
        $texte = str_replace(self::PAR_INU3, '', $texte);
        $texte = str_replace(self::PAR_INU4, '', $texte);
        $texte = str_replace(self::PAR_INU5, '', $texte);
        $texte = str_replace('•', '<br />•', $texte);
        return trim($texte);
    }

    // ------------------------------------------
    //   reirer les ToolTips de la chaine
    // ------------------------------------------
    protected function textSansToolTips($chaineHtml) {

        $texte = $chaineHtml->plaintext;

        foreach ($chaineHtml->find('a[class=tooltip]') as $toolTip) {
            foreach ($toolTip->find('span') as $span) {
                $texte = trim(str_replace($span->plaintext, '', $texte));
            }
        }
        return $texte;
    }

    // ------------------------------------------
    //   récupérer les liens de la page détail
    // ------------------------------------------
    protected function liensPageDetails($lienUrl) {
        $urlseul = html::urlDunLien($lienUrl);
        $pageDetail = file_get_html($urlseul);

        if ($pageDetail == false) { // Non trouvée
            return self::PAGE_DET_NON_TRV;
        } else { // on traite la page   
            $liens = '';
            $listeLiens = '';
            //$i=0;
            $fichier = $pageDetail->find('p[class=pdf_link]', 0)->outertext;
            //foreach ($pageDetail->find('p[class=pdf_link]') as $fichier) {
            $lien = trim($fichier);
            $lien = html::urlDunLien($lien); // lien 'nu' sans domaine
            $lien = self::DOMAINE_ASN . $lien; // on ajoute le domaine
            $fichier = $pageDetail->find('p[class=pdf_link]', 0)->plaintext;
            $lien = html::lienHtml($lien, trim($fichier)); // on habille en html
            $listeLiens .= $lien . html::B_BR;
            //$i++;
            //echo html::parHtmlBal($i.' '. $listeLiens);
            //}
        }
        //echo html::parHtmlBal($liens);
        return $listeLiens;
    }

    //-------------------------------------------------
    //  déterminer le nombre de pages 
    //-------------------------------------------------
    protected function trouverTotalPages($html) {
        $page = 0;
                
        foreach ($html->find('ul.pages > li') as $article) {  // ->find('a')
            $page = $article->plaintext;
        }
        return $page;
    }

    //-------------------------------------------------
    //  déterminer l'url de la page suivante
    //-------------------------------------------------
    protected function trouverUrlPageSuivante($numPage) {
        $aTraiter = $this->html->find('ul[class=pages] > li[class=next]', 0);

        foreach ($aTraiter->find('a') as $article) {
            $lienUrl = html::urlDunLien($article); // lien 'nu' sans domaine
            $lienUrl = self::DOMAINE_ASN . $lienUrl; // on ajoute le domaine
            return $lienUrl;
        }
    }

    //-------------------------------------------------
    //  Trouver l'installation dans la chaine
    //-------------------------------------------------
    protected function trouveInstallDansLibelle($libelleInst) {
        $lstInstallations = InstallationsPrioritaires::genereListeToutesInstallations();

        foreach ($lstInstallations as $installation) {
            $position = strpos($libelleInst, $installation);

            if ($position != false) {
                //echo html::parHtmlBal('Pos='.$position.' - inst='.$installation);
                $retour = $installation;
                return $retour;
            }
        }
        return '';
    }

    // Diverses fonctions utilitaires

    protected function alimenterFinInfos($item, $url) {
        $item['url'] = $url;
        $item['dateCapture'] = date('Y/m/j'); // date du jour
        $item['origine'] = 'ASN';
        $item['page'] = '';
        $item['gravite'] = '';
        return $item;
    }

    protected function trouveDatePublication($libelle) {
        if(preg_match('/\d{2}\/\d{2}\/\d{4}/', $libelle)){
            $libelle = preg_replace('/^.*(\d{2}\/\d{2}\/\d{4}).*$/', '$1', $libelle); 
            return substr($libelle, 6,4) . '-' . substr($libelle, 3,2) . '-' . substr($libelle, 0,2);
        }
        
        $position = strpos($libelle, 'le '); // premier 'le '
        if ($position !== false) {
            $dateBrute = substr($libelle, ($position + 3));
//echo html::parHtmlBal('$date publication Brute : ' . $dateBrute);
            $dateAMJ = rezo::dateMoisEnLettreversJMA($dateBrute);
            return $dateAMJ;
        }
        echo $libelle . " n'est pas reconnu comme une date";
    }

    /*     * ****************************************
     *   Afficher tous les postes du tableau
     * ******************************************* */

    public function afficherDumpInfosItems($item) {
        if (array_key_exists('id', $item)) {
            echo Html::parHtmlBal('id = ' . $item['id'], 'div');
        }
        if (array_key_exists('installation', $item)) {
            echo Html::parHtmlBal('installation = ' . $item['installation'], 'div');
        }
        if (array_key_exists('libelleInstallation', $item)) {
            echo Html::parHtmlBal('libelleInstallation = ' . $item['libelleInstallation'], 'div');
        }
        if (array_key_exists('titre', $item)) {
            echo Html::parHtmlBal('titre = ' . $item['titre'], 'div');
        }
        if (array_key_exists('dateIncident', $item)) {
            echo Html::parHtmlBal('dateIncident = ' . $item['dateIncident'], 'div');
        }
        if (array_key_exists('texte', $item)) {
            echo Html::parHtmlBal('texte = ' . $item['texte'], 'div');
        }
        if (array_key_exists('texteDetail', $item)) {
            echo Html::parHtmlBal('texteDetail = ' . $item['texteDetail'], 'div');
        }
        if (array_key_exists('fichierTelecharge', $item)) {
            echo Html::parHtmlBal('fichierTelecharge = ' . $item['fichierTelecharge'], 'div');
        }
        if (array_key_exists('url', $item)) {
            echo Html::parHtmlBal('url = ' . $item['url'], 'div');
        }
        if (array_key_exists('dateCapture', $item)) {
            echo Html::parHtmlBal('dateCapture = ' . $item['dateCapture'], 'div');
        }
        if (array_key_exists('origine', $item)) {
            echo Html::parHtmlBal('origine = ' . $item['origine'], 'div');
        }
        if (array_key_exists('page', $item)) {
            echo Html::parHtmlBal('page = ' . $item['page'], 'div');
        }
        if (array_key_exists('gravite', $item)) {
            echo Html::parHtmlBal('gravite = ' . $item['gravite'], 'div');
        }
    }

    /* protected function __destruct() {
      //$this->html->clear();
      unset($this->html);
      $this->infosItem = null;
      } */
    

        
}

?>
