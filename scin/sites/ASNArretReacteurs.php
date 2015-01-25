<?php
ini_set('display_errors','on'); error_reporting(E_ALL & ~E_STRICT & ~E_NOTICE & ~E_DEPRECATED);   // DEBUGGING              & ~E_WARNING
?>
<?php

//*******************************************
//     Arrêt de réacteurs (pages spécifiques)
//*******************************************
class ASNArretReacteurs extends ASNabstract {

    
    private $url_root = 'http://www.asn.fr';
    private $url = 'http://www.asn.fr/Controler/Actualites-du-controle/Arret-de-reacteurs-de-centrales-nucleaires';
    private $url_centrale = FALSE;//'http://www.asn.fr/filtre/filtre_arret/%28limit%29/99/%28classID%29/reactor_shutdown/%28nodeID%29/93954/%28inb%29/';
    // http://www.asn.fr/index.php/Les-actions-de-l-ASN/Le-controle/Actualites-du-controle/Arret-de-reacteurs-de-centrales-nucleaires
//private $listeCentrale = array();
    private $centrale;
    private $centrale_libelle;
    private $ligneInfo;

    function __construct() {
//echo 'new ASNArretReacteurs';
    }

    /*     * *****************************************************
      récupérer les infos répétitives spécifiques à la page
     * ******************************************************** */

    protected function alimenterInfos($url) {

        $item = array();
        $item['installation'] = preg_replace('/Centrale\s\w+\sd.\s/', '', $this->centrale);

        if($this->ligneInfo == NULL){
            echo '<br>La page est vide</br>';
            return;
        }
        
        // le texte contient des Tipstools à virer
        $texte = $this->ligneInfo->plaintext;
        if($texte == NULL){
            //var_dump( $this->ligneInfo);
            echo '<br>Impossible de trouver la page "' . $url . '"</br>';
            return;
        }
        $item['texte'] = preg_replace('/\sLire la suite\s*\.*$/', '', trim($texte));

        $item['libelleInstallation'] = $this->centrale_libelle;//$libelleInstallation;

        $installMAJ = $this->ligneInfo->find('div[class=desc] div[class=intro]', 0);  // spécifique
        $item['titre'] = trim($installMAJ->plaintext);

        $publieLe = $this->ligneInfo->find('p[class=meta]', 0)->plaintext;  // spécifique
        $datePublicationAMJ = $this->trouveDatePublication(trim($publieLe));
        //echo '<br>$datePublicationAMJ = ' . $datePublicationAMJ;
        $item['dateIncident'] = $datePublicationAMJ;

// on récupère le lien en ajoutant le domaine
        $lienUrl = $this->ligneInfo->find('p[class=view_link] a', 0)->href;
        $lienUrl = $this->ajoutDomaineALUrl(trim($lienUrl));
        $item['urlDetail'] = $lienUrl;
        $item['texte'] = str_replace('Lire la suite', '<a href="' . $lienUrl . '" target="_blank">Lire de la suite...</a>', $item['texte'] );
// le détail est sur une autre page, il faut suivre l'url et la traiter
        $pageDetail = $this->traiterPageDetail($lienUrl);
            
        if ($pageDetail == self::PAGE_DET_NON_TRV) {
            $pageDetail .= ' ' . html::urlDunLien($lienUrl);
        }
// Il faut stocker les liens de la page détail
        $liensPageDetail = $this->traiterLiensPageDetail($lienUrl);
        $item['fichierTelecharge'] = $liensPageDetail;

        $item['texteDetail'] = $pageDetail;
        $item = $this->alimenterFinInfos($item, $url);
        $item['origine'] = 'ASN arret';
        
        /*echo '<pre>';
        var_dump($item);
        echo '</pre>';*/
        
// on stocke les infos
        $this->infosItem[] = $item;
    }

    /*     * *****************************************************
     * Recupérer la page demandee
     * ******************************************************** */

    public function recupererPageListe($url, $centrale) {
//$rang = array_search($centrale, $this->listeCentrale);
        $toutesCentrales = $this->getListCentrales();
        // Le site connait les noms de centrales avec "Centrale nucléaire de " en préfixe
        foreach ($toutesCentrales as $centrale_inlist) {
            if(strlen($centrale_inlist) > strlen($centrale)
               && substr($centrale_inlist, strlen($centrale_inlist)-strlen($centrale)) == $centrale){
                $centrale = $centrale_inlist;
            }
        }
        $this->centrale_libelle = $this->centrale = urldecode($centrale);
        $url = $this->url_centrale . $centrale; 
        $this->html = file_get_html($url);

        if ($this->html == false) { // Non trouvée
            $this->alimenterErrNonTrouve($url);
        } else { // on traite la page  
            $i = -9999;  // 0;
            echo "<br>Analyse de " . urldecode($centrale);
            //echo "<br>depuis " . $url;
            foreach ($this->html->find('div[Class="view resume"]', 0) as $ligneInfo) {
                if($ligneInfo->innertext == null){
                    continue;
                }
                //echo "<pre>" . print_r( $ligneInfo->plaintext , true) . '</pre>';
            
                $this->ligneInfo = $ligneInfo;
                $this->alimenterInfos($url);

                if ($i++ > 5) {
                    break;
                }
            }
        }
    }

    public function traiterLiensPageDetail($lienUrl) {
        $urlseul = html::urlDunLien($lienUrl);
        //echo '<br> $urlseul = ' . $urlseul;
        $pageDetail = file_get_html($urlseul);

        if ($pageDetail == false) { // Non trouvée
            return self::PAGE_DET_NON_TRV;
        } else { // on traite la page   
            $liensPageDetail = '';
            $href_done = array();
            foreach ($pageDetail->find('ul[class="list"]') as $liste) {
// trouver le texte préalable ??
                foreach ($liste->find('li') as $ligne) {
                    $lien = $ligne->innertext;
                    $lien = trim($lien);
                    $fichier = $this->textSansToolTips($ligne); //$ligne->plaintext;

                    $lien = html::urlDunLien($lien); // lien 'nu' sans domaine
                    if ($lien != '') {
                        if (substr($lien, 0, 4) != 'http')
                            $lien = self::DOMAINE_ASN . $lien; // on ajoute le domaine
                        if(array_key_exists($lien,$href_done))
                            continue;
                        $href_done[$lien] = 1;
                        $lien = html::lienHtml($lien, trim($fichier)); // on habille en html
                    } else {
                        $lien = trim($fichier);
                    }
                    $liensPageDetail .= $lien . html::B_BR;
                }
            }
        }
        return $liensPageDetail;
    }

    public function dernierArretCentrales() {
        //$toutesCentrales = InstallationsPrioritaires::genereListeCentralesEnFonction();
    
        $toutesCentrales = $this->getListCentrales();
        foreach ($toutesCentrales as $centrale) {
            $this->centrale = $centrale;
            $url = $this->url_centrale . $centrale;
            $this->html = file_get_html($url);
            
            //echo '<pre>' . htmlentities( $this->html) . '</pre>';
            
            //echo "<br>$url";
            if ($this->html == false) { // Non trouvée
                $this->alimenterErrNonTrouve($url);
            } else { // on traite la page
                $this->centrale_libelle = $this->centrale = urldecode($centrale);
                $this->ligneInfo = $this->html->find('div[Class="view resume"]', 0);
                $this->alimenterInfos($url);
            }
            //break;//DEBUG
        }
    }

    /* Initialise la liste des centrales ainsi que l'url de base pour le détail par centrale */
    public function getListCentrales() {
        $html = file_get_html($this->url);
        $items = array();
        $index = 0;
        //$html->find('div[id=div_filtre_installation]', 0)->dump_node(true);
        //$html->find('#div_filtre_installation .filtre_dropdown li', 0)->dump_node(true);
        $liste = $html->find('#div_filtre_installation .filtre_dropdown', 0);
        foreach($liste->find('li a') AS $a){
            $href = $a->getAttribute('href');
            if(strpos($href, '(inb)') === FALSE)
                continue;
            $centrale = preg_replace('/^.*(\/\(inb\)\/)([^\/]+)\/.*$/', '$2', $href);
            if(!$this->url_centrale){
                $this->url_centrale = $this->url_root . preg_replace('/(^.*\/)(\(inb\)\/[^\/]+\/)(.*)$/', '$1$3/(inb)/', $href);
            }
            $items[] = $centrale;
        }
        return $items;
    }

    protected function trouveDatePublication($libelle) {
        if(preg_match('/\d{2}\/\d{2}\/\d{4}/', $libelle))
            return substr($libelle, 6,4) . '-' . substr($libelle, 3,2) . '-' . substr($libelle, 0,2);
        $publieLe = $libelle;
        $position = strpos($publieLe, ' - '); // premier ' - '
        if ($position !== false) {
            //echo Html::parHtmlBal('date OK 1 : ' . $publieLe);
            $publieLe = substr($publieLe, 0, $position);
            //echo Html::parHtmlBal('date OK 2 : ' . $publieLe);
            $position = strpos($libelle, 'le '); // premier 'le '
            if ($position !== false) {
                //echo Html::parHtmlBal('date OK 3 : ' . $publieLe);
                $dateBrute = substr($libelle, ($position + 3));
                $dateAMJ = rezo::dateJMAversAMJstr($dateBrute);
                return $dateAMJ;
            }
        }
        return '';
    }

    public function getUrl() {
        return $this->url;
    }

}

?>
