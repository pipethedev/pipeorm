<?php

namespace Core\Database;

class Builder
{
    private string $table = '';
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

    public function table($table): Builder
    {
        $this->table = $table;
        return $this;
    }

    public function select($select): Builder
    {
        if(is_string($select)){
            if(!in_array($select, $this->select)){
                $this->select[] = $select;
            }
        }else if(is_array($select)){
            $this->select = $select;
        }
        return $this;
    }

    public function orderBy(string $column, string $arrangement = 'ASC'): Builder
    {
        $this->orderBy = "order by ".$column.' '.$arrangement;
        return $this;
    }

    public function limit(int $limit): Builder
    {
        $this->limit = "limit ". $limit;
        return $this;
    }

    public function offset($offset): Builder
    {
        $this->limit = "offset ". $offset;
        return $this;
    }

    public function orWhere($column, string $operator = null, string $value = null): Builder
    {
        $arguments = func_get_args();

        $totalArguments = count($arguments);
 
        if(empty($this->where))
        {
            $this->where = "where ";
        } 
        if($totalArguments == 3)
        {
            if($this->isAndMultipleWhereClause || $this->IsOrMultipleWhereClause) {
             $this->where .= " or ";
            }

            $this->where.=$column." ".$operator." '".$value. "'";

            $this->IsOrMultipleWhereClause = true;

        }else if($totalArguments == 1){
            if(!empty($this->where)) $this->where .= " or ";

            $this->where.= " ( ";
            $column($this);
            $this->isAndMultipleWhereClause = false;
            $this->isAndMultipleWhereClause = false;
            $this->where.= " ) ";
        }
        return $this;
    }

    public function where($column, string $operator = null, string $value = null): Builder
    {

       // Treat where cases for functions, array and string
       $arguments = func_get_args();

       $totalArguments = count($arguments);

       if(empty($this->where)) $this->where = "where ";

       if($totalArguments == 3)
       {
           if($this->isAndMultipleWhereClause) {
            $this->where = $this->where. " and ";
           }
           $this->where.=$column." ".$operator." '".$value. "'";

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
        }else{
            if($this->where != "where ") {
                $this->where = $this->where. " and ";
            }
            $this->where.= " ( ";
            
            $this->isAndMultipleWhereClause= false;

            // Here $body is a closure(callback function)
            $column($this);

            $this->where.= " ) ";
        }
       }
       return $this;
    }

    public function orWhereIn(string $column, array $data)
    {
        $this->operationForInOr($column, $data);
        $this->IsOrMultipleWhereClause = true;
        return $this;
    }

    public function whereIn(string $column, array $data)
    {
        $this->operationForInOr($column, $data);
        $this->isAndMultipleWhereClause = true;
        return $this;
    }

    protected function operationForInOr(string $column, array $data)
    {
        if(empty($this->where)) $this->where = "where ";

        if($this->isAndMultipleWhereClause || $this->IsOrMultipleWhereClause) {
            $this->where = $this->where. " and ";
        }

        $this->where.=$column." in (".implode("','", $data).")";
    }

    private function build(): string
    {
        if(empty($this->table)) throw new \Exception("Table name is required");

        $sqlCommand = count($this->select) == 0 ? '*': implode(",", $this->select);

        $sqlCommand = "select ".$sqlCommand." from ".$this->table. " ";
        
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