<?php

namespace Core;
use PDO;

class MysqlConnection {
    private static $instance;

    private $pdo;

    private function __construct()
    {
        // TODO: Connection details come from environment variables
        $this->pdo = new PDO('mysql:host=localhost;dbname=blog', 'root', '');
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance->pdo;
    }
}