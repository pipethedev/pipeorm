<?php

$root = dirname(__FILE__);

$root = str_replace("\\", '/', $root);

define('ROOT', $root);
require_once ROOT."/Core/autoload.php";
require_once ROOT."/Core/helpers.php";

use Core\Database\Builder;

$builder = new Builder();

echo $builder->table('mentors')->select(['first_name', 'last_name'])->orderBy('id', 'desc')->where([
    'age' => 30
])->where(fn($builder) => $builder->where('name', '=', 'pipe'))->get();