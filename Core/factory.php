<?php

namespace Core;

class DatabaseFactory {
    const MYSQL = "mysql";
    const POSTGRESQL = "postgresql";
    const MONGODB = "mongo";

    public static function getDriver(string $driver){
        switch ($driver){
            case self::POSTGRESQL:
                return new PostgreSQL();
            case self::MONGODB:
                return new Mongo();
            default:
                return new Mysql();
        }
    }
}