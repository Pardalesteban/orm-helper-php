<?php
declare(strict_types=1);

namespace Pardalesteban\OrmHelper;

use PDO;
use InvalidArgumentException;

final class queryBuilder{
    private PDO $pdo; 
    private string $table;
    private string $primaryKey;
    /**  @var string[]|null */
    private ?array $columns = null;

    /** @var list<string> */
    private array $wheres = [];

    /** @var array<string,mixed> */
    private array $params = [];

    /** @var list<string> */
    private array $groupBy = [];

    /** @var list<string> */
    private array $orderBy = [];

    private ?int $limit = null;
    private ?int $offset = null;

    /** @var list<string> */
    private array $joins = []; //example: "inner join profiles on users.id = profile.user_id"

    public function __construct(PDO $pdo, string $table, string $primaryKey = 'id'){
        $this->pdo = $pdo;
        $this->table = $table;
        $this->primaryKey = $primaryKey;
    }

    /**
     * avoid an name inyection
     * */

    private function quoteIdent(string $name): string{
        if(!preg_match('/^[A-Za-z0-9_]+(\.[A-Za-z0-9_]+)?$/', $name)){
            throw new InvalidArgumentException("Invalid indentificator: $name");
        }
        if (strpos($name, '.') !== false){
            [$t, $c] = explode('.', $name, 2);
            return "`$t`.`$c`";
        }
        return "`$name`";
    }

    /** 
     * generate and save a unique parameter's name and return his placeholder 
     * */
    private function pushParam(string $prefix, mixed $value): string{
        $i = count($this->params);
        $k = ":{$prefix}_{$i}";
        $this->params[$k] = $value;
        return $k;
    }


    // ====================================
    //            PUBLIC FUNCTION
    // ====================================
    /** @param string[]|null $cols */
    
    public function select(?array $cols = null){
        $this->columns = $cols;
        return $this;
    }

    /** 
     * WHERE CONDITION, manual bind ("age>:a", ['a'=>18])
     */

    public function where(string $expr, array $bind = []): self{
        $this->wheres[] = $expr;
        foreach($bind as $k => $v){
            $ph = is_string($k) && $k !== '' && $k[0] !== ':' ? ":k" : $k; 
            $this->params[$ph] = $v;
        }
        return $this;
    }

    /**
     * Not manual bind
     */

     public function whereEq(string $col, mixed $val): self{
        $ph = $this->pushParam('w', $val);
        $this->wheres[] = $this->quoteIdent($col)." = $ph";
        return $this;
     }

     public function whereMo(string $col, mixed $val): self{
        //Terminar de escribir la funcion
        return $this;
     }
}   
