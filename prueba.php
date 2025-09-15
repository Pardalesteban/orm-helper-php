<?php
    //This is a local example for testing the ORM functionality, using XAMPP and not using Composer vendor directory
    require __DIR__.'vendor/autoload.php';

    use Pardalesteban\OrmHelper\Conn;
    use Pardalesteban\OrmHelper\queryBuilder;

    //Here we create a database connection
    $conn = new Conn('localhost', 'datos', 'root', '');
    $pdoConn = $conn->getConnection();

    $users = new queryBuilder($pdoConn, "usuarios", "ID");
    $users->select();
    echo $users;

?>