<?php

//*******************************************
//     Lettres de suite d'inspection
//*******************************************
class ASNConsultPublic extends ASNabstract {

    private $url = 'http://www.asn.fr/Reglementer/Consultations-du-public';

    function __construct() {
        self::recupererPageListe($this->url);
    }

// récupérer les infos répétitives spécifiques à la page
    protected function alimenterInfos($url) {
        $item = array();

        foreach ($this->html->find('div[class=factbox-content] > ul > li') as $ligneInfo) {
            $item['titre'] = trim($ligneInfo->find('a', 0)->plaintext);  // spécifique
            if(!$item['titre']) continue;
            $installMAJ = $ligneInfo->find('ul li em',0);
            $item['texte'] = $installMAJ->plaintext;

            $item['libelleInstallation'] = '';
//             
            $date = $installMAJ->plaintext;
            //$date = preg_replace('/^.*(\d{2}\/\d{2}\/\d{4}).*$/', '$1', $date);  //  'Inspection du '
            //$date = rezo::dateJMAversAMJstr($date);
            //$item['dateIncident'] = $date;

            // le détail est sur une autre page
            $item['texteDetail'] = '';

            // lien sur fichier à télécharger
            $fichier = $ligneInfo->find('a[href]', 0);
            $lienUrl = trim($fichier->href);
            $lienUrl = self::DOMAINE_ASN . $lienUrl; // on ajoute le domaine
            $lienUrl = html::lienHtml($lienUrl, trim($fichier->plaintext)); // on habille en html
            $item['fichierTelecharge'] = $lienUrl;

            $item = $this->alimenterFinInfos($item, $url);
                        
            // on stocke les infos
            $this->infosItem[] = $item;
        }
    }

}

?>
