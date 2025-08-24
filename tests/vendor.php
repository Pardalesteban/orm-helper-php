<?php
    //This example is using Composer's autoloader
    require_once(__DIR__ . '/vendor/autoload.php');

    //use Pardalesteban\OrmHelper\Conn;
    //use Pardalesteban\OrmHelper\Orm;
    
    //Here we create a database connection
    $conn = new Conn('localhost', 'test_db', 'root', '');
    $pdo = $conn->getConnection();

    //Here we create an ORM object with the table, primary key, and PDO connection
    $orm = new Orm('users', 'id', $pdo);

    $orm->select('*')->where('id', 1)->execute();
    $result = $orm->fetchAll();

    echo json_encode($result);

    $orm->insert(['name' => 'John Doe', 'email' => 'john@example.com']);

    ?>