<?php

namespace Core;


class DatabaseFactory {
    const MYSQL = "mysql";
    const POSTGRESQL = "postgresql";
    const MONGO = "mongo";

    static function getDriver(string $driver){
        switch ($driver){
            case self::MYSQL:
                return new Mysql();
        }
    }
}