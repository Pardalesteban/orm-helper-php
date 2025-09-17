<?php
    //This is a local example for testing the ORM functionality, using XAMPP and not using Composer vendor directory
    require_once __DIR__ . '/src/Conn.php';
    require_once __DIR__ . '/src/queryBuilder.php';
    
    use Pardalesteban\OrmHelper\Conn;
    use Pardalesteban\OrmHelper\queryBuilder;

    //Here we create a database connection
    $conn = new Conn('localhost', 'datos', 'root', '');
    $pdoConn = $conn->getConnection();

    $users = new queryBuilder($pdoConn, "usuarios", "ID");
    $users->select();
    $users->get();
    echo $users;


?>
