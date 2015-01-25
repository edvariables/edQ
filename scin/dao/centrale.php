<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of centrales
 *
 * @author jm
 */
final class Centrale {

    // Type de centrales
    const EN_FONCTIONNEMENT = 1;
    const EN_DEMANTELEMENT = 2;

    /** @var int */
    private $id;
    /** @var string */
    private $centrale;
    /** @var int */
    private $type;
    
    function __construct($centrale, $type) {
        $this->id = null;
        $this->centrale = $centrale;
        $this->type = $type;
    }

    public function getId() {
        return $this->id;
    }

    public function getCentrale() {
        return $this->centrale;
    }

    public function setCentrale($centrale) {
        $this->centrale = $centrale;
    }

    public function getType() {
        return $this->type;
    }

    public function setType($type) {
        $this->type = $type;
    }
        
    public static function tousTypes() {
        return array(
            self::EN_FONCTIONNEMENT,
            self::EN_DEMANTELEMENT,
        );
    }
    
}

?>
