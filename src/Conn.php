<?php
    namespace Pardalesteban\OrmHelper;
    use PDO;
    
    class Conn{
        protected $conn;

        public function __construct($host, $dbname, $user, $pass) {
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];
            $this->conn = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass, $options);
        }

        public function getConnection() {
            return $this->conn;
        }
    }
?>