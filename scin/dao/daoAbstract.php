<?php

include_once '../sites/ASNAbstract.php';

abstract class DaoAbstract extends ASNabstract { 

    /** @var PDO */
    protected $db = null;

    const DEBUG = false;

    function __construct() {
        
    }

    public function __destruct() {
        $this->db = null;  // fermer la connexion
    }
    
    /**
     * @return PDO
     */
    protected function getDb() {
        if ($this->db !== null) {
            return $this->db;
        }
        $config = Config::getConfig("db");
        if (self::DEBUG)
            var_dump($config);
        try {
            $this->db = new PDO($config['dsn'], $config['username'], $config['password']);
        } catch (Exception $ex) {
            throw new Exception('Erreur de connexion DB : ' . $ex->getMessage());
        }
        return $this->db;
    }
    
    protected function executeStatement(PDOStatement $statement, array $params) {
        if (!$statement->execute($params)) {
            self::throwDbError($this->getDb()->errorInfo());
        }
    }

    /**
     * @return PDOStatement
     */
    protected function query($sql) {
        $statement = $this->getDb()->query($sql, PDO::FETCH_ASSOC);
        if ($statement === false) {
            self::throwDbError($this->getDb()->errorInfo());
        }
        return $statement;
    }

    protected static function throwDbError(array $errorInfo) {
        // TODO log error, send email, etc.
        throw new Exception('Erreur DB [' . $errorInfo[0] . ', ' . $errorInfo[1] . ']: ' . $errorInfo[2]);
    }

    protected static function formatDateTime(DateTime $date) {
        return $date->format(DateTime::ISO8601);
    }


    protected function alimenterInfos($url) {
       // vide 
    }
    
}

?>
