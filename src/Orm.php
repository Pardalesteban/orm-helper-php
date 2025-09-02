<?php
    namespace Pardalesteban\OrmHelper;
    use PDO;

    class Orm {
        protected $table;
        protected $primaryKey;
        protected $db;

        public function __construct($table, $primaryKey, PDO $conn) {
            $this->table = $table;
            $this->primaryKey = $primaryKey;
            $this->db = $conn;
        }

        public function select($id = null, $fields = '*', $options = []) {
            // $options puede incluir: 'where', 'groupBy', 'orderBy', 'limit'
            $sql = "SELECT $fields FROM {$this->table}";
            $params = [];

            if ($id !== null) {
                $sql .= " WHERE {$this->primaryKey} = :id";
                $params['id'] = $id;
            } elseif (!empty($options['where'])) {
                $sql .= " WHERE " . $options['where'];
                if (!empty($options['params'])) {
                    $params = $options['params'];
                }
            }

            if (!empty($options['groupBy'])) {
                $sql .= " GROUP BY " . $options['groupBy'];
            }
            if (!empty($options['orderBy'])) {
                $sql .= " ORDER BY " . $options['orderBy'];
            }
            if (!empty($options['limit'])) {
                $sql .= " LIMIT " . $options['limit'];
            }

            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public function selectAll() {
            $stmt = $this->db->prepare("SELECT * FROM {$this->table}");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public function insert($data) {
            $columns = array_keys($data);
            $placeholders = array_map(fn($col) => ":$col", $columns);
            $sql = "INSERT INTO {$this->table} (" . implode(", ", $columns) . ") VALUES (" . implode(", ", $placeholders) . ")";
            $stmt = $this->db->prepare($sql);
            $stmt->execute($data);
            return $this->db->lastInsertId();
            
        }

        public function delete($primaryKey) {
            $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE {$this->primaryKey} = :primaryKey");
            $stmt->execute(['primaryKey' => $primaryKey]);
        }

        public function createTable($columns) {
            // $columns debe ser un array asociativo: ['nombre' => 'VARCHAR(100)', 'email' => 'VARCHAR(255)', ...]
            $columnDefs = implode(", ", array_map(
                fn($name, $type) => "$name $type",
                array_keys($columns),
                $columns
            ));
            $sql = "CREATE TABLE IF NOT EXISTS {$this->table} (id INT AUTO_INCREMENT PRIMARY KEY, $columnDefs)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
        }

        public function deleteTable() {
            $sql = "DROP TABLE IF EXISTS {$this->table}";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
        }

        public function union($otherTable, $fields = '*', $where = '', $orderBy = '', $limitSQL = '') {
            // $fields puede ser '*' o 'users.id, users.nombre, orders.total'
            $sql = "SELECT $fields FROM {$this->table} ";
            $sql .= "UNION SELECT $fields FROM $otherTable";
            if ($where) {
                $sql .= " WHERE $where";
            }
            if ($orderBy) {
                $sql .= " ORDER BY $orderBy";
            }
            if ($limitSQL) {
                $sql .= " LIMIT $limitSQL";
            }
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public function changeTable($newTable, $newPrimaryKey = null) {
            $this->table = $newTable;
            if ($newPrimaryKey) {
                $this->primaryKey = $newPrimaryKey;
            }
        }

        public function innerJoin($otherTable, $onCondition, $fields = '*', $options = []) {
            $sql = "SELECT $fields FROM {$this->table} INNER JOIN $otherTable ON $onCondition";
            $params = [];
            if (!empty($options['where'])) {
                $sql .= " WHERE " . $options['where'];
                $params = $options['params'] ?? [];
            }
            if(!empty($options['groupBy'])) {
                $sql .= " GROUP BY " . $options['groupBy'];
            }
            if (!empty($options['orderBy'])) {
                $sql .= " ORDER BY " . $options['orderBy'];
            }
            if (!empty($options['limit'])) {
                $sql .= " LIMIT " . $options['limit'];
            }
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public function leftJoin($otherTable, $onCondition, $fields = '*', $options = []) {
            $sql = "SELECT $fields FROM {$this->table} LEFT JOIN $otherTable ON $onCondition";
            $params = [];
            if (!empty($options['where'])) {
                $sql .= " WHERE " . $options['where'];
                $params = $options['params'] ?? [];
            }
            if(!empty($options['groupBy'])) {
                $sql .= " GROUP BY " . $options['groupBy'];
            }
            if (!empty($options['orderBy'])) {
                $sql .= " ORDER BY " . $options['orderBy'];
            }
            if (!empty($options['limit'])) {
                $sql .= " LIMIT " . $options['limit'];
            }
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public function rightJoin($otherTable, $onCondition, $fields = '*', $options = []) {
            $sql = "SELECT $fields FROM {$this->table} RIGHT JOIN $otherTable ON $onCondition";
            $params = [];
            if (!empty($options['where'])) {
                $sql .= " WHERE " . $options['where'];
                $params = $options['params'] ?? [];
            }
            if(!empty($options['groupBy'])) {
                $sql .= " GROUP BY " . $options['groupBy'];
            }
            if (!empty($options['orderBy'])) {
                $sql .= " ORDER BY " . $options['orderBy'];
            }
            if (!empty($options['limit'])) {
                $sql .= " LIMIT " . $options['limit'];
            }
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public function fullOuterJoin($otherTable, $onCondition, $fields = '*', $options = []) {
            $sql = "SELECT $fields FROM {$this->table} FULL OUTER JOIN $otherTable ON $onCondition";
            $params = [];
            if (!empty($options['where'])) {
                $sql .= " WHERE " . $options['where'];
                $params = $options['params'] ?? [];
            }
            if(!empty($options['groupBy'])) {
                $sql .= " GROUP BY " . $options['groupBy'];
            }
            if (!empty($options['orderBy'])) {
                $sql .= " ORDER BY " . $options['orderBy'];
            }
            if (!empty($options['limit'])) {
                $sql .= " LIMIT " . $options['limit'];
            }

            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public function join($tables = [], $onCondition=[], $innerOptions=[], $fields=[], $where=[]){
        $sql = "SELECT  " .implode(',', $fields). " FROM {$this->table} $innerOptions[0] JOIN $tables[0] ON $onCondition[0]";
        if(count($tables) != count($innerOptions)){
            return "ERROR: YOU MUST PROVIDE INNER JOIN OPTIONS FOR EACH TABLE";
        }
        if(count($tables) > 1){
            for($i=1;$i<count($tables);$i++){
                $sql .= " $innerOptions[$i] JOIN $tables[$i] ON $onCondition[$i]";
            }
        }
        if(!empty($where)){
            $sql .= " WHERE " .  $where[0];
            if(count($where) > 1){
                for($i=1;$i<count($where);$i++){
                    $sql .= " AND " . $where[$i];
                }
            }
        }
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
    
?>