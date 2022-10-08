<?php

namespace Core\Database;

class Builder
{
    private $table = '';
    private $select = [];
    private $where = '';
    private $join = '';
    private $orderBy = '';
    private $groupBy = '';
    private $having = '';
    private $offset = '';
    private $limit = '';
    private $commandString = '';

    private $select_command_clauses = array("join", "where", "groupBy", "having", "offset", "limit", "orderBy") ;

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

    public function buildSelect()
    {
        $command = "";
        if(count($this->select) == 0){
             $command = $this->table.'*';
        }else {
            $command = implode(",", $this ->select);
        }
        $command = "select ".$command." from ".$this->table. " ";
        foreach ($this->select_command_clauses as $clause){
            if(!empty($this->$clause)){
                $command.-$this->$clause. ""
            }
        }
        $command = substr();
    }
}