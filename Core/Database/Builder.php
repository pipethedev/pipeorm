<?php

namespace Core\Database;

class Builder
{
    private string $table = '';
    private array $select = [];
    private string $where = '';
    private string $join = '';
    private string $orderBy = '';
    private string $groupBy = '';
    private string $having = '';
    private string $offset = '';
    private string $limit = '';
    private string $commandString = '';

    private array $select_command_clauses = array("join", "where", "groupBy", "having", "offset", "limit", "orderBy");
    private bool $appendKeyword = false;

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

    public function where(string $column, string $operator = null, string $value = null): Builder
    {
//        $args = func_get_args();
//        if(empty($this->where))
//        {
//            $this->where = "where ";
//        }
//        if(count($args) == 3)
//        {
//            if(){}
//        }
    }

    private function buildSelectCommand(): void
    {
        if(count($this->select) == 0){
             $sqlCommand = '*';
        }else {
            $sqlCommand = implode(",", $this->select);
        }
        $sqlCommand = "select ".$sqlCommand." from ".$this->table. " ";
        foreach ($this->select_command_clauses as $clause){
            // e.g $this->orderBy(), $this->limit()
            if(!empty($this->$clause)){
                $sqlCommand = $sqlCommand." ".$this->$clause. " ";
            }
        }
        $sqlCommand = substr($sqlCommand, 0, strrpos($sqlCommand, ' '));

        $this->commandString = $sqlCommand;

    }


    public function get(): string
    {
        $this->buildSelectCommand();
        return $this->commandString;
    }
}