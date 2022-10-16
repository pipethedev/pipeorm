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

    private array $aggregateCommands = array("join", "where", "groupBy", "having", "offset", "limit", "orderBy");
    private bool $isMultipleWhereClause = false;

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

    public function orWhere()
    {

    }

    public function where($body, string $operator = null, string $value = null): Builder
    {
       $arguments = func_get_args();

       $totalArguments = count($arguments);

       if(empty($this->where))
       {
           $this->where = "where ";
       }
       if($totalArguments == 3)
       {
           if($this->isMultipleWhereClause){
            $this->where = $this->where. " and ";
           }
           $this->where.=$body." ".$operator." '".$value. "'";
           $this->isMultipleWhereClause = true;
       }else if($totalArguments == 1){
        if(is_array($body)){
            foreach($body as $key => $value){
                if($this->isMultipleWhereClause){
                    $this->where = $this->where. " and ";
                }
                $this->where.=$key." = '".$value. "'";
                $this->isMultipleWhereClause = true;
            }
        }else{
            if($this->where != "where "){
                $this->where = $this->where. " and ";
            }
            $this->where.= " ( ";
            $this->isMultipleWhereClause= false;
            // Here $body is a closure(callback function)
            $body($this);
            $this->where.= " ) ";
        }
       }
       return $this;
    }

    private function buildCommand(): string
    {
        $sqlCommand = count($this->select) == 0 ? '*': implode(",", $this->select);

        $sqlCommand = "select ".$sqlCommand." from ".$this->table. " ";
        
        foreach ($this->aggregateCommands as $clause){
            // e.g $this->orderBy(), $this->limit()
            if(!empty($this->$clause)){
                $sqlCommand = $sqlCommand." ".$this->$clause. " ";
            }
        }
        return substr($sqlCommand, 0, strrpos($sqlCommand, ' '));
    }


    public function get(): string
    {
        return $this->buildCommand();
    }
}