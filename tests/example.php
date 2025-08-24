<?php
    //If you use locally use this libraries

    require_once(__DIR__ . '/../src/orm.php');
    require_once(__DIR__ . '/../src/conn.php');

    //if you use Composer's autoloader use

    //require_once(__DIR__ . '/../vendor/autoload.php');
    //use pardalesteban/OrmHelper/Conn;
    //use pardalesteban/OrmHelper/Orm;



    //Here we create a database connection
    $conn = new Conn('localhost', 'test_db', 'root', '');
    $pdo = $conn->getConnection();

    //Here we create an ORM object with the table, primary key, and PDO connection
    $users = new Orm('users', 'id', $pdo);
    //You can now use the ORM methods for database operations

    $table = [
        'nombre' => 'VARCHAR(100)',
        'email' => 'VARCHAR(255)',
        'edad' => 'INT'
    ];
    $users->createTable($table);
    echo "Tabla 'users' creada con éxito.";

    $name = "Esteban Pardal";
    $email = "esteban@example.com";
    $age = 18;

    $usersData = $users->selectAll();
    $exists = false;
    foreach ($usersData as $user) {
        if($user['email'] == $email) {
            echo "El usuario con email $email ya existe.";
            $exists = true;
            break;
        }
    }
    if (!$exists) {
        $users->insert(['nombre' => $name, 'email' => $email, 'edad' => $age]);
        echo "Usuario insertado con éxito.";
    }

    $usersData = $users->selectAll();
    echo "<pre>";
    print_r($usersData);
    echo "</pre>";
?>