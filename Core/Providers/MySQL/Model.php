<?php

namespace Core\Providers;

use Core\MysqlConnection;

class MySQLModel extends MysqlConnection {
    protected $pdo;
    protected $table;

    public function __construct()
    {
        $this->pdo = MysqlConnection::getInstance();
    }
}