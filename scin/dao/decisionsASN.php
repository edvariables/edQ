<?php

class DecisionsASN {

    /** @var int */
    private $id;

    /** @var string */
    private $idASN;

    /** @var string */
    private $installation;

    /** @var string */
    private $libelleInstallation;

    /** @var string */
    private $dateDecision;

    /** @var string */
    private $datePublication;

    /** @var string */
    private $texte;

    /** @var string */
    private $fichierTelecharge;

    /** @var string */
    private $url;

    /** @var string */
    private $dateCapture;

    function __construct() {
        
    }

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getIdASN() {
        return $this->idASN;
    }

    public function setIdASN($idASN) {
        $this->idASN = $idASN;
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

    public function getDateDecision() {
        return $this->dateDecision;
    }

    public function setDateDecision($dateDecision) {
        $this->dateDecision = $dateDecision;
    }

    public function getDatePublication() {
        return $this->datePublication;
    }

    public function setDatePublication($datePublication) {
        $this->datePublication = $datePublication;
    }

    public function getTexte() {
        return $this->texte;
    }

    public function setTexte($texte) {
        $this->texte = $texte;
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

    public function getDateCapture() {
        return $this->dateCapture;
    }

    public function setDateCapture($dateCapture) {
        $this->dateCapture = $dateCapture;
    }

}

?>
