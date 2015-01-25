<?php

/**
 * Description of ASNrss
 *
 * @author jm
 */
class ASNrss {

    private $fluxRssXml;
    private $dateCapture;

    const URL_RSS = 'http://www.asn.fr/index.php/flux_rss/RSS?flux=general';

    /*     * ****************************************************
     *  contructeur : 
     * ******************************************************** */

    public function __construct() {
        
    }

    /*     * ****************************************************
     *  Récupération du flux rss
     * ******************************************************** */

    public function recupereRss() {
        $url = $this::URL_RSS;
        // on récupère la page
        $this->fluxRssXml = file_get_html($url);
        $this->dateCapture = date('d-m-Y');
    }

    public function afficheRss() {
        echo $this->fluxRssXml;
    }
    
    public function sauvegardeFichierXmlFluxRss() {
        // TODO 
    }
    
    public function getFluxRssXml() {
        return $this->fluxRssXml;
    }

    public function setFluxRssXml($fluxRssXml) {
        $this->fluxRssXml = $fluxRssXml;
    }

    public function getDateCapture() {
        return $this->dateCapture;
    }

    public function setDateCapture($dateCapture) {
        $this->dateCapture = $dateCapture;
    }


}

?>
