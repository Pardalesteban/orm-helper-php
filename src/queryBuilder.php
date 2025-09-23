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

    private ?array $lastresult = [];

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
            $ph = (is_string($k) && $k !== '' && $k[0] !== ':') ? ":$k" : $k;
            $this->params[$ph] = $v;
        }
        return $this;
    }

    /**
     * Not manual bind
     */

    
     public function innerJoin(string $otherTable, mixed $pm1, mixed $pm2): self{
        $this->joins[] = "inner join ".$this->quoteIdent($otherTable)." on ".$this->table.".".$this->quoteIdent($pm1)."=".$this->quoteIdent($otherTable).".".$this->quoteIdent($pm2);   
        return $this;
     }
     
     public function leftJoin(string $otherTable, mixed $pm1, mixed $pm2): self{
        $this->joins[] = "left join ".$this->quoteIdent($otherTable)." on ".$this->quoteIdent($pm1)."=".$this->quoteIdent($pm2);   
        return $this;     
     }

     public function rightJoin(string $otherTable, mixed $pm1, mixed $pm2): self{
        $this->joins[] = "left join ".$this->quoteIdent($otherTable)." on ".$this->quoteIdent($pm1)."=".$this->quoteIdent($pm2);   
        return $this;     
     }

     public function whereEq(string $col, mixed $val): self{
        $ph = $this->pushParam('w', $val);
        $this->wheres[] = $this->quoteIdent($col)." = $ph";
        return $this;
     }

     public function whereMo(string $col, mixed $val): self{
        $ph = $this->pushParam('w', $val);
        $this->wheres[] = $this->quoteIdent($col)." > $ph";
        return $this;
     }

     public function whereLe(string $col, mixed $val): self{
        $ph = $this->pushParam('w', $val);
        $this->wheres[] = $this->quoteIdent($col)." < $ph";
        return $this;
     }

     public function whereDif(string $col, mixed $val): self{
        $ph = $this->pushParam('w', $val);
        $this->wheres[] = $this->quoteIdent($col)." != $ph";
        return $this;
     }

     public function order(string $col, string $dir = 'ASC'): self{
        $dir = strtoupper($dir) === 'DESC' ? 'DESC' : 'ASC';
        $this->orderBy[] = $this->quoteIdent($col)." $dir";
        return $this;
     }

     public function limit(string $n, ?int $offset = null): self{
        $this->limit = max(0,$n);
        $this->offset = $offset !== null ? max(0, $offset) : null;
        return $this;
     }
    
     //get the result of the SQL sentence
     public function get(): array{
        $stmt = $this->pdo->prepare($this->toSql());
        foreach($this->params as $k => $v){
            $stmt->bindValue($k, $v);
        }
        $stmt->execute();
        $this->lastresult = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $this->lastresult;
     }
     //Creates the SQL sentence
     public function toSql(): string {
        $cols = $this->columns === null || $this->columns === [] 
            ? '*'
            : implode(', ', array_map(fn($c) => $this->quoteIdent($c), $this->columns));

        $sql = "SELECT {$cols} FROM " . $this->quoteIdent($this->table);

        if ($this->joins) {
            $sql .= ' ' . implode(' ', $this->joins);
        }
        if ($this->wheres) {
            $sql .= ' WHERE ' . implode(' AND ', $this->wheres);
        }
        if ($this->groupBy) {
            $sql .= ' GROUP BY ' . implode(', ', array_map(fn($c) => $this->quoteIdent($c), $this->groupBy));
        }
        if ($this->orderBy) {
            $sql .= ' ORDER BY ' . implode(', ', $this->orderBy);
        }
        if ($this->limit !== null) {
            $sql .= ' LIMIT ' . $this->limit;
            if ($this->offset !== null) {
                $sql .= ' OFFSET ' . $this->offset;
            }
        }
        return $sql;
    }
 

     public function __toString(): string{
        if($this->lastresult !== null){
            return json_encode($this->lastresult, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
        }
        return $this->toSql();
     }
}   
