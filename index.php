<?php

$root = dirname(__FILE__);

$root = str_replace("\\", '/', $root);

define('ROOT', $root);
require_once ROOT."/Core/autoload.php";
require_once ROOT."/Core/helpers.php";

//$dotenv = \Dotenv\Dotenv::createImmutable(__DIR__);
//$dotenv->safeLoad();

use Core\User;
use Core\Database\Database;

$user = new User();

$database = new Database();

//$result = $database->query("select * from mentors");

$result = $database->fetch("select * from mentors", User::class);

var_dump($result);

var_dump($user);