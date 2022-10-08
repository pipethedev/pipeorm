<?php

namespace Core\Database;

class Database
{
    private $connection;

    public function __construct()
    {
        $this->connection = new \mysqli('us-cdbr-east-06.cleardb.net', 'bd60a68cb50f4b', '43189fcf', 'heroku_c91005d515ff088');

        if($this->connection->connect_error){
            die($this->connection->error);
        }
    }

    public function query($sql = "")
    {
        return $this->connection->query($sql);
    }

    /**
     * @throws \Exception
     */
    public function fetch($sql, $class=\stdClass::class)
    {
        $result = $this->query($sql);

        if(!$result){
            throw new \Exception($this->connection->error);
        }
        $out =  array();
        while ($row = $result->fetch_object($class)){
            array_push($out, $row);
        }
        $result->free();
        return $out;
    }

    public function insertGetId($sql)
    {
        $result = $this->query($sql);
        if($result){
            return $this->connection->insert_id;
        }
        return new \Exception($this->connection->error);
    }

    /**
     * @throws \Exception
     */
    public function insert($sql)
    {
        $result = $this->query($sql);
        if($result){
            return $this->connection->affected_rows;
        }
        throw new \Exception($this->connection->error);
    }

    public function update($sql)
    {
        $result = $this->query($sql);
        if($result){
            return $this->connection->affected_rows;
        }
        throw new \Exception($this->connection->error);
    }
}