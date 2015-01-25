<?php
error_reporting(E_WARNING & ~E_NOTICE & ~E_DEPRECATED); // PRODUCTION
ini_set('display_errors','on'); error_reporting(E_ALL & ~E_STRICT & ~E_WARNING & ~E_NOTICE & ~E_DEPRECATED);   // DEBUGGING

class ASNAvisDispoPublic extends ASNabstract {

    private $url = 'http://www.asn.fr/Reglementer/Consultations-du-public/Avis-de-mise-a-disposition-du-public-organisee-par-les-exploitants';

    function __construct() {
        self::recupererPageListe($this->url);
    }

// récupérer les infos répétitives spécifiques à la page
    protected function alimenterInfos($url) {
        $item = array();

        foreach ($this->html->find('div[class=view resume]') as $ligneInfo) {
	    if(! is_object($ligneInfo)) continue;
	    //var_dump($ligneInfo);
            // on inverse titre et installation pour la présentation
            $installation = trim($ligneInfo->find('h3', 0)->plaintext);  // spécifique

            if ($installation != '') {
                //$item['libelleInstallation'] = $installation;
                $texte = $ligneInfo->find('div[class=intro]', 0)->plaintext;
                $item['texte'] = $texte;

                $titre = $ligneInfo->find('h3', 0)->plaintext;
                $texte = trim(str_replace($titre, '', $texte));
                $item['titre'] = trim($titre);

                /*$date = $ligneInfo->find('p[class=meta]', 0)->plaintext;
                $date = preg_replace('/^.*(\d{2}\/\d{2}\/\d{4}).*$/', '$1', $date);  //  'Courrier du '
                $date = rezo::dateJMAversAMJstr($date);
                $item['dateIncident'] = $date;*/

                $item['texteDetail'] = ''; // pas de détail
		$item['fichierTelecharge'] = '';
                // liens sur fichier(s) à télécharger (1 : pdf, 2 = lien IRSN)
		foreach ($ligneInfo->find('p[class=view_link] a') as $fichier) {
                    $lienUrl = trim($fichier->outertext);
                    $lienUrl = html::urlDunLien($lienUrl); // lien 'nu' sans domaine
		    if(substr($lienUrl, 0, 4) != 'http') {
		        $lienUrl = self::DOMAINE_ASN . $lienUrl; // on ajoute le domaine
		    }
                    $lienUrl = html::lienHtml($lienUrl, trim($fichier->plaintext)); // on habille en html
                    $item['fichierTelecharge'] .= $lienUrl . html::B_BR;
		}
                foreach ($ligneInfo->find('p[class=file]') as $fichier) {
                    $lienUrl = trim($fichier->outertext);
                    $lienUrl = html::urlDunLien($lienUrl); // lien 'nu' sans domaine
		    if(substr($lienUrl, 0, 4) != 'http') {
                        $lienUrl = self::DOMAINE_ASN . $lienUrl; // on ajoute le domaine
		    }
                    $lienUrl = html::lienHtml($lienUrl, trim($fichier->plaintext)); // on habille en html
                    $item['fichierTelecharge'] .= $lienUrl . html::B_BR;
                }

                $item = $this->alimenterFinInfos($item, $url);
                // on stocke les infos
                $this->infosItem[] = $item;
            }
        }
    }

}

?>
