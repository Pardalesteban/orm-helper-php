<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

echo "prueba<br>";
    //This is a local example for testing the ORM functionality, using XAMPP and not using Composer vendor directory
    require_once __DIR__ . '/php/Conn.php';
    require_once __DIR__ . '/php/queryBuilder.php';
    
    use Pardalesteban\OrmHelper\Conn;
    use Pardalesteban\OrmHelper\queryBuilder;

    //Here we create a database connection
    $conn = new Conn('localhost', 'datos', 'root', '');
    $pdoConn = $conn->getConnection();

    $users = new queryBuilder($pdoConn, "usuarios", "ID");
    $users->select();
    $rows = $users->get();
    echo $users;
    echo $rows;


?>
