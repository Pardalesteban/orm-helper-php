<?php
declare(strict_types=1);

namespace Pardalesteban\OrmHelper;

use PDO;

/**
 * ORM: entrypoint of the library 
 * Recive a PDO created by conn.php
 * show table($name, $pramaryKey) to obtain fluid QueryBuilder  
 */

final class ORM{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->pdo = $pdo;
    }

    /**
     * Create a QueryBuilder for an specific table
     */

     public function pdo(): PDO {
        return $this->pdo;
     }
}