<?php

class YoPdoMysql {
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

    protected function __construct() {}
    private function __clone() {}     //防止克隆
    public function __wakeup() {}    //防止反序列化

    /**
     * @return PDO|void
     */
    public function getDb() {
        if (!$this->_dbh) {
            try {
				//启用 PDO 持久连接
				$driverOptions = array(
                    PDO::ATTR_PERSISTENT => DB_PCONNECT,
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,    //打开异常模式
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,   //以数组形式返回
                    //PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
                );
				$dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';

                $contect = new PDO($dsn, DB_USER, DB_PASSWORD, $driverOptions);
                if ($contect) {
                    $this->_dbh = $contect;
                }
            } catch (PDOException $e) {
                if (DEBUG_MODE != 'production') {
                    print "Error!: " . $e->getMessage() . "<br/>";
                }
                die("Database connection failed.");
            }
        }
        return $this->_dbh;
    }
}
?>