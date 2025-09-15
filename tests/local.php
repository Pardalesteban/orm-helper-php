<?php
    //This is a local example for testing the ORM functionality, using XAMPP and not using Composer vendor directory
    require_once(__DIR__ . '/../src/orm.php');
    require_once(__DIR__ . '/../src/queryBuilder.php');
    require_once(__DIR__ . '/../src/Conn.php');

    //Here we create a database connection
    $conn = new Conn('localhost', 'test_db', 'root', '');
    $pdo = $conn->getConnection();

?>