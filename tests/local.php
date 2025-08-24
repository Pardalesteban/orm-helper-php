<?php
    //This is a local example for testing the ORM functionality, using XAMPP and not using Composer vendor directory
    require_once(__DIR__ . '/../src/orm.php');
    require_once(__DIR__ . '/../src/conn.php');

    //Here we create a database connection
    $conn = new Conn('localhost', 'test_db', 'root', '');
    $pdo = $conn->getConnection();

    //Here we create an ORM object with the table, primary key, and PDO connection
    $orm = new Orm('users', 'id', $pdo);
    //You can now use the ORM methods for database operations

    $table = [
        'nombre' => 'VARCHAR(100)',
        'email' => 'VARCHAR(255)',
        'edad' => 'INT'
    ];
    $orm->createTable($table);
    echo "Tabla 'users' creada con éxito.";
?>