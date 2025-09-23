<?php
    require 'libr.php';
    use Pardalesteban\OrmHelper\Conn;
    use Pardalesteban\OrmHelper\queryBuilder;

    //Here we create a database connection
    $conn = new Conn('localhost', 'telefonia', 'root', '');
    $pdoConn = $conn->getConnection();

    $users = new queryBuilder($pdoConn, "celulares", "ID");
    $users->select()
          ->innerJoin("empleados", "legajo", "legajo");
    $users->get();
    echo $users;


?>
