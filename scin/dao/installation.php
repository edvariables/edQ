<?php

/*
 * Liste des isntallations prioritaires à surveiller
 */

class Installation {
  
    /** @var int */
    private $id;

    /** @var string */
    private $installation;
    
    function __construct($id, $installation) {
        $this->id = $id;
        $this->installation = $installation;
    }
    public function getId() {
        return $this->id;
    }

    public function getInstallation() {
        return $this->installation;
    }

    public function setInstallation($installation) {
        $this->installation = $installation;
    }

}

?>
