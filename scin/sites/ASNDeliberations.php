<?php

class ASNDeliberations extends ASNabstract {

    protected $url = 'http://www.asn.fr/Reglementer/Bulletin-officiel-de-l-ASN/Deliberations-de-l-ASN';

    function __construct() {
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

        foreach ($this->html->find('div[class=view resume]') as $ligneInfo) {
            $texte = self::textSansToolTips($ligneInfo); // le texte contient des Tipstools à virer
            $item['texte'] = trim($texte);

            $item['installation'] = '';
            $installation = self::trouveInstallDansLibelle($texte);
            if ($installation != '') {
                $item['installation'] = $installation;
            }

            // installation et titre  
            $libelleInstallation = $installation;//trim($ligneInfo->find('h3 > a', 0)->plaintext);
            $libelleInstallation = trim($ligneInfo->find('h3 > a', 0)->plaintext);
            $item['libelleInstallation'] = $libelleInstallation;

            $item['titre'] = trim($ligneInfo->find('h3 > a', 0)->plaintext);

            $dateAvisAMJ = $this->trouveDateAvis(trim($libelleInstallation));
            //var_dump($libelleInstallation);
            $item['dateIncident'] = $dateAvisAMJ;
            $publieLe = $ligneInfo->find('p[class=meta]', 0)->plaintext;  // spécifique
            $datePublicationAMJ = $this->trouveDatePublication(trim($publieLe));
            $item['texteDetail'] = $datePublicationAMJ; 
            
            // enlever libellé et publié du texte
            $item['texte'] = trim($ligneInfo->find('div[class=desc] div[class=intro]', 0)->plaintext);
            //
            // on récupère le lien en ajoutant le domaine
            $lienUrl = $ligneInfo->find('h3 > a', 0)->href;
            if(substr($lienUrl, 0, 4) != 'http') {
                $lienUrl = self::DOMAINE_ASN . $lienUrl; // on ajoute le domaine
            }
            $lienUrl = html::lienHtml($lienUrl, trim($fichier->plaintext)); // on habille en html
            
            $item['fichierTelecharge'] = $lienUrl;
            $item = $this->alimenterFinInfos($item, $url);
            // on stocke les infos
            $this->infosItem[] = $item;
            
            //break;//DEBUG
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
                $dates = 'Date de l\'avis : ' . $item['dateIncident'];
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
     * Forme du code avis à isoler :
     * n° 2013-AV-0189 de
     * ----------------------------------------- */

    const RECH_NO = 'n°';
    const RECH_NO2 = 'no';
    const RECH_D = 'd';

    private function recupererDecision($libelleInstallation) {
        $avis = '';

// n°  
        $position = strpos($libelleInstallation, self::RECH_NO);
        if ($position == false) {
// deuxième chance       
            $position = strpos($libelleInstallation, self::RECH_NO2);
        }
        if ($position == false) {
// erreur… laisser la chaîne vide               
        } else {
// on traite (on gère les cas avec ou sans espace)
            if (substr($libelleInstallation, $position + 2, 1) == ' ') {
                $delta = 3;
                $depart = $position + 3;
            } else {
                $delta = 2;
                $depart = $position + 2;
            }
            $position2 = strpos($libelleInstallation, self::RECH_D, $position);
            $depart = $position + $delta;
            $longueur = $position2 - $position - $delta;
            $avis = trim(substr($libelleInstallation, $depart, $longueur));
        }
        return $avis;
    }

    /* ----------------------------------------- 
     * 
     * ----------------------------------------- */

    private function trouveDateAvis($libelle) {
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
