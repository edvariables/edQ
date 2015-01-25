<?php

//*******************************************
//     Lettres de suite d'inspection
//*******************************************
class ASNSuiteDinspection extends ASNabstract {

    private $url = 'http://www.asn.fr/Controler/Actualites-du-controle/Lettres-de-suite-d-inspection-des-installations-nucleaires';

    function __construct() {
        self::recupererPageListe($this->url);
    }

// récupérer les infos répétitives spécifiques à la page
    protected function alimenterInfos($url) {
        $item = array();

        foreach ($this->html->find('div[class=view resume]') as $ligneInfo) {
            $item['titre'] = trim($ligneInfo->find('h3', 0)->plaintext);  // spécifique
            
            $installMAJ = $ligneInfo->find('div[class=intro] p[class=subtitle]',0);
            $item['texte'] = $installMAJ->plaintext;

            $libelleInstallation = '';
            foreach ($installMAJ->nodes as $paragraphe) {
                if($paragraphe->tag == 'text'
                   && trim($paragraphe->plaintext)){
                    if($libelleInstallation)
                        $libelleInstallation .= html::B_BR;
                    $libelleInstallation .= $paragraphe->plaintext;
                   }
            }
            $item['libelleInstallation'] = trim($libelleInstallation);
//             
            //$date = $ligneInfo->find('span[class=bold]', 0)->plaintext; // pas génial, mais y'a que ça            
            $date = $ligneInfo->find('p[class=meta]', 0)->plaintext;
            $texte = trim(str_replace($date, '', $texte));
            //$date = rezo::dateJMAversAMJstr(trim($date));
            //$date = trim(substr($date, 12));  //  'Inspection du '
            $date = preg_replace('/^.*(\d{2}\/\d{2}\/\d{4}).*$/', '$1', $date);  //  'Inspection du '
            $date = rezo::dateJMAversAMJstr($date);
            $item['dateIncident'] = $date;

            // le détail est sur une autre page
            $item['texteDetail'] = '';

            // lien sur fichier à télécharger
            $fichier = $ligneInfo->find('div[class=file] a[href]', 0);
            $lienUrl = trim($fichier->href);
            $lienUrl = self::DOMAINE_ASN . $lienUrl; // on ajoute le domaine
            $lienUrl = html::lienHtml($lienUrl, trim($fichier->plaintext)); // on habille en html
            $item['fichierTelecharge'] = $lienUrl;

            $item = $this->alimenterFinInfos($item, $url);
            // on stocke les infos
            $this->infosItem[] = $item;
        }
    }

    /*     * *****************************************************
     * Recupérer la page demandee
     * cf ASNDecisions
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
                // 'http://www.asn.fr/index.php/content/view/full/81//(offset)/60'
                // ça démarre à 15 avec un incrément de 15
                $urlSuite = $this->trouverUrlPageSuivante($numPage);
                $posFin = strlen($urlSuite) - 2;
                $debutUrl = substr($urlSuite, 0, $posFin);
                $finUrl = 0;

                $totalPages = 2; // spécifique pour Suites d'inspection, ça fait beaucoup !

                for ($numPage = 2; $numPage <= $totalPages; $numPage++) {
                    $finUrl += 15;
                    $urlSuite = $debutUrl . $finUrl;
                    //var_dump($urlSuite);
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

}

?>
