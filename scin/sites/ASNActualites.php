<?php

//*******************************************
//     Actualités
//*******************************************
class ASNActualites extends ASNabstract {

    private $url = 'http://www.asn.fr/Informer/Actualites';

    function __construct() {
        $b_UNE_SEULE_PAGE = true;
        self::recupererPageListe($this->url, $b_UNE_SEULE_PAGE);
    }

// récupérer les infos répétitives spécifiques à la page
    protected function alimenterInfos($url) {

        $item = array();

        foreach ($this->html->find('div[id=contenu]', 0)->find('div[class="view resume"]') as $ligneInfo) {
            //$item['texte'] = $ligneInfo->plaintext;
            $texte = self::textSansToolTips($ligneInfo); // le texte contient des Tipstools à virer
            $item['texte'] = preg_replace('/\sLire la suite\s*\.*$/', '', trim($texte));

            /* $item['titre'] = $ligneInfo->find('a[class=title]', 0)->plaintext;  
              $installMAJ = $ligneInfo->find('span[class=date]', 0);  // spécifique
              $item['libelleInstallation'] = $installMAJ->plaintext; */

            // inversion, installation et titre, il semble que ce soit un bogue…
            $installation = $ligneInfo->find('a[class=title]', 0)->plaintext;
            $item['libelleInstallation'] = $installation;

            $installMAJ = $ligneInfo->find('h3', 0);  // spécifique
            $item['titre'] = trim($installMAJ->plaintext);

            if (false) {
                $date = $ligneInfo->find('p[class=meta]', 0)->plaintext; // pas génial, mais y'a que ça            
                $texte = trim(str_replace($date, '', $texte));
                $date = rezo::dateJMAversAMJstr(trim($date));
                $item['dateIncident'] = $date;
            }

            $item['dateIncident'] = '';

            // le détail est sur une autre page
            $item['texteDetail'] = '';

            // on récupère le lien en ajoutant le domaine
            $installation = $ligneInfo->find('p[class=view_link] a', 0)->outertext;
            $installation = self::ajoutDomaineALUrl(trim($installation));
            $item['fichierTelecharge'] = $installation;
            $item = $this->alimenterFinInfos($item, $url);
            // on stocke les infos
            $this->infosItem[] = $item;
        }
    }

}

?>
