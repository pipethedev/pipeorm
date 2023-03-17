<?php

namespace Core\Providers\MySQL;

use Core\Connections\MySQL;
use Core\Providers\MySQL\Builder;

abstract class BaseModel extends MySQL {

    protected $pdo;
    protected $table;

    public function __construct()
    {
        $this->pdo = MySQL::getInstance();
    }

    public static function execute(): Builder
    {
        return new Builder;
    }
}