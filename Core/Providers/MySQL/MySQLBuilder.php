<?php

namespace Core\Providers\MySQL;

use Core\Interfaces\IBuilder;

class MySQLBuilder implements IBuilder
{
    protected string $table;
    private array $select = [];
    private string $where = '';
    private string $having = '';
    private string $join = '';
    private string $orderBy = '';
    private string $groupBy = '';
    private string $offset = '';
    private string $limit = '';

    private array $commands = array("join", "where", "offset", "limit", "orderBy", "groupBy", "having",);
    private bool $isAndMultipleWhereClause = false;
    private bool $IsOrMultipleWhereClause = false;

    public function __construct(string $table = 'users')
    {
        $this->table = $table;
    }

    public static function table($table): MySQLBuilder
    {
        return new MySQLBuilder($table);
    }

    public function select($select): MySQLBuilder
    {
        $this->select = is_array($select) ? $select : func_get_args();

        return $this;
    }

    public function orderBy(string $column, string $arrangement = 'ASC'): MySQLBuilder
    {
        $this->orderBy = "ORDER BY ".$column.' '.$arrangement;
        return $this;
    }

    public function limit(int $limit): MySQLBuilder
    {
        $this->limit = "LIMI ". $limit;
        return $this;
    }

    public function offset($offset): MySQLBuilder
    {
        $this->limit = "OFFSET ". $offset;
        return $this;
    }

    public function orWhere($column, ?string $operator = null, ?string $value = null): MySQLBuilder
    {
        $totalArguments = func_num_args();
 
        if(empty($this->where))
        {
            $this->where = "WHERE ";
        } 
        if($totalArguments == 3)
        {
            if($this->isAndMultipleWhereClause || $this->IsOrMultipleWhereClause) {
             $this->where .= " OR ";
            }

            $this->where .= "{$column} {$operator} '{$value}'";

            $this->IsOrMultipleWhereClause = true;

        }else if(is_callable($column)) {
            if(!empty($this->where)) $this->where .= " OR ";

            $this->where.= " ( ";
            $this->isAndMultipleWhereClause = false;
            $this->isAndMultipleWhereClause = false;
            $column($this);
            $this->where.= " ) ";
        }
        return $this;
    }

    public function where($column, ?string $operator = null, ?string $value = null): MySQLBuilder
    {
       $totalArguments = func_num_args();

       if(empty($this->where)) $this->where = "WHERE ";

       if($totalArguments == 3)
       {
           if($this->isAndMultipleWhereClause) $this->where .= " and ";

           $this->where .= "{$column} {$operator} '{$value}'";

           $this->isAndMultipleWhereClause = true;
       }else if($totalArguments == 1) {
        if(is_array($column)){
            foreach($column as $key => $value){
                if($this->isAndMultipleWhereClause) {
                    $this->where = $this->where. " and ";
                }
                $this->where.=$key." = '".$value. "'";

                $this->isAndMultipleWhereClause = true;
            }
        }else if(is_callable($column)){
            // Situations where we have : where(function($query) { ... })
            if($this->where != "WHERE ") {
                $this->where .= " and ";
            }
            $this->where.= " ( ";
            
            $this->isAndMultipleWhereClause= false;
            
            $column($this);

            $this->where.= " ) ";
        }
       }
       return $this;
    }

    public function orWhereIn(string $column, array $data): MySQLBuilder
    {
        $this->operationForInOr($column, $data);
        $this->IsOrMultipleWhereClause = true;
        return $this;
    }

    public function whereIn(string $column, array $data): MySQLBuilder
    {
        $this->operationForInOr($column, $data);
        $this->isAndMultipleWhereClause = true;

        // Every possiblity there would be several method chaining for where clause
        return $this;
    }

    public function join(): MySQLBuilder
    {
        $this->join = "JOIN ".func_get_args()[0]." ON ".func_get_args()[1]. " ".func_get_args()[2]." ".func_get_args()[3];

        return $this;
    }

    protected function operationForInOr(string $column, array $data)
    {
        if(empty($this->where)) $this->where = "WHERE ";

        if($this->isAndMultipleWhereClause || $this->IsOrMultipleWhereClause) {
            $this->where = $this->where. " and ";
        }

        $this->where.=$column." IN ('".implode("','", $data)."')";
    }

    private function build(): string
    {
        if(empty($this->table)) throw new \Exception("Table name is required");

        $command = count($this->select) == 0 ? '*': implode(",", $this->select);

        $sqlCommand = "SELECT ".$command." FROM ".$this->table. "";
        
        foreach ($this->commands as $clause){
            // e.g $this->orderBy(), $this->limit()
            if(!empty($this->$clause)){
                $sqlCommand = $sqlCommand." ".$this->$clause. " ";
            }
        }
        return substr($sqlCommand, 0, strrpos($sqlCommand, ' '));
    }

    public function get(): string
    {
        return $this->build();
    }
}