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

    private array $select_command_clauses = array("join", "where", "groupBy", "having", "offset", "limit", "orderBy") ;

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

    private function buildSelectCommand(): void
    {
        if(count($this->select) == 0){
             $sqlCommand = '*';
        }else {
            $sqlCommand = implode(",", $this->select);
        }
        $sqlCommand = "select ".$sqlCommand." from ".$this->table. " ";
        foreach ($this->select_command_clauses as $clause){
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