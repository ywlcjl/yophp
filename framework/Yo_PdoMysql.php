<?php

class Yo_PdoMysql {
    //单例实例化
    private static $_instance;
    
    //dbh
    private $_dbh;

    //单例模式
    public static function getInstance()
    {
        if (!(self::$_instance instanceof self)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    
    public function __construct() {

    }
    
    public function getDb() {
        if (!$this->_dbh) {
            try {
                $contect = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASSWORD);
                if ($contect) {
                    $this->_dbh = $contect;
                    $this->_dbh->query("SET NAMES 'UTF8'");
                }
            } catch (PDOException $e) {
                if (DEBUG_MODE != 'production') {
                    print "Error!: " . $e->getMessage() . "<br/>";
                }
                die();
            }
        }
        return $this->_dbh;
    }

}
?>