<?php

//*******************************************
//     Avis d'incidents
//*******************************************
class ASNAvisDincidents extends ASNabstract {

    protected $url = 'http://www.asn.fr/Controler/Actualites-du-controle/Avis-d-incidents-des-installations-nucleaires/';

    function __construct() {
        self::recupererPageListe($this->url);
        //self::recupererPageListe($this->url.'2012'); // année précédente
    }

// récupérer les infos répétitives spécifiques à la page
    protected function alimenterInfos($url) {
        $item = array();
        $i = -9999; //0;
        $html = $this->html;
        //foreach ($niveau1->find('li') as $ligneInfo) {
        foreach ($this->html->find('div[id=contenu] > div[class="view resume"]') as $ligneInfo) {
            $texte = self::textSansToolTips($ligneInfo); // le texte contient des Tipstools à virer

            $titre = $ligneInfo->find('h3', 0)->plaintext;
            $item['titre'] = $titre;
            $texte = trim(str_replace($titre, '', $texte));
            $texte = preg_replace('/\sLire la suite\s*\.*$/', '', $texte);
            
            $libelleInstallation = $ligneInfo->find('div[class=intro] > p.subtitle', 0)->plaintext;
            $item['libelleInstallation'] = $libelleInstallation;
            $texte = trim(str_replace($libelleInstallation, '', $texte));

            $date = $ligneInfo->find('p[class=meta]', 0)->plaintext; // pas génial, mais y'a que ça            
            $texte = trim(str_replace($date, '', $texte));
            $date = rezo::dateJMMMMAversAMJstr(trim($date));
            $item['dateIncident'] = $date;

            $item['texte'] = $texte;

            $item['installation'] = '';
            $installation = $this->trouveInstallDansLibelle($libelleInstallation);
            if ($installation == '') {
                $installation = $this->trouveInstallDansLibelle($texte);
            }
            $item['installation'] = $installation;

            // on récupère le lien en ajoutant le domaine
            $lienUrl = $ligneInfo->find('a[class=view_link]', 0)->outertext;
            $lienUrl = self::ajoutDomaineALUrl(trim($lienUrl));
            $item['fichierTelecharge'] = $lienUrl;

            // le détail est sur une autre page, il faut suivre l'url et la traiter
            /*$pageDetail = $this->traiterPageDetail($lienUrl);
            if ($pageDetail == self::PAGE_DET_NON_TRV) {
                $pageDetail .= ' ' . html::urlDunLien($lienUrl);
            }*/
            $pageDetail = $ligneInfo->find('div[class=intro] > p.subtitle + p', 0)->plaintext;
            
            $item['texteDetail'] = $pageDetail; 
            $item = $this->alimenterFinInfos($item, $url);
            // on stocke les infos
            $this->infosItem[] = $item;

            // juste le début pour test (initialiser $i à 0)
            $i++;
            if ($i > 5) {
                break;
            }
        }
    }

}

?>
