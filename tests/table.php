<?php
    //firstly you need to call the files and create the connection
    require_once(__DIR__ . '/../src/orm.php');
    require_once(__DIR__ . '/../src/conn.php');

    $conn = new Conn('localhost', 'test_db', 'root', '');
    $pdo = $conn->getConnection();

    //this is an example of an class for an specific table
    class UserTable extends Orm {
        public function __construct(PDO $conn) {
            // Call the parent constructor with the specific table and primary key
            parent::__construct('users', 'id', $conn);
        }

        //Now you can add more specific methods for the users table here
        public function findByEmail($email) {
            $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE email = :email");
            $stmt->execute(['email' => $email]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
    }
?>