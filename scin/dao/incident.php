<?php

/*

 */

final class Incident {

    /** @var int */
    private $id;

    /** @var string */
    private $installation;

    /** @var string */
    private $libelleInstallation;

    /** @var int */
    private $gravite;

    /** @var string */
    private $titre;

    /** @var DateTime */
    private $dateIncident;

    /** @var string */
    private $texte;

    /** @var string */
    private $texteDetail;

    /** @var string */
    private $fichierTelecharge;

    /** @var string */
    private $url;

    /** @var string */
    private $page;

    /** @var DateTime */
    private $dateCapture;
    
    /** @var string */
    private $origine;
        
    function __construct() {
        //echo html::parHtmlBal('Nouvel incident créé');
    }
   
    public function getId() {
        return $this->id;
    }
    
    public function setId($id) {
        $this->id = $id;
    }
        public function getInstallation() {
        return $this->installation;
    }

    public function setInstallation($installation) {
        $this->installation = $installation;
    }

    public function getLibelleInstallation() {
        return $this->libelleInstallation;
    }

    public function setLibelleInstallation($libelleInstallation) {
        $this->libelleInstallation = $libelleInstallation;
    }

    public function getGravite() {
        return $this->gravite;
    }

    public function setGravite($gravite) {
        $this->gravite = $gravite;
    }

    public function getTitre() {
        return $this->titre;
    }

    public function setTitre($titre) {
        $this->titre = $titre;
    }

    public function getDateIncident() {
        return $this->dateIncident;
    }

    public function setDateIncident($dateIncident) { //  (DateTime $dateIncident) {
        $this->dateIncident = $dateIncident;
    }

    public function getTexte() {
        return $this->texte;
    }

    public function setTexte($texte) {
        $this->texte = $texte;
    }

    public function getTexteDetail() {
        return $this->texteDetail;
    }

    public function setTexteDetail($texteDetail) {
        $this->texteDetail = $texteDetail;
    }

    public function getFichierTelecharge() {
        return $this->fichierTelecharge;
    }

    public function setFichierTelecharge($fichierTelecharge) {
        $this->fichierTelecharge = $fichierTelecharge;
    }

    public function getUrl() {
        return $this->url;
    }

    public function setUrl($url) {
        $this->url = $url;
    }

    public function getPage() {
        return $this->page;
    }

    public function setPage($page) {
        $this->page = $page;
    }

    public function getDateCapture() {
        return $this->dateCapture;
    }

    public function setDateCapture($dateCapture) { //  (DateTime $dateCapture) {
        $this->dateCapture = $dateCapture;
    }
    
    public function getOrigine() {
        return $this->origine;
    }

    public function setOrigine($origine) {
        $this->origine = $origine;
    }



}

?>
