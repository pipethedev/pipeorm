<?php


$root = dirname(__FILE__);

$root = str_replace("\\", '/', $root);

define('ROOT', $root);
require_once ROOT."/Core/autoload.php";
require_once ROOT."/Core/helpers.php";

use Core\Implementation\Models\User;


// echo $builder->table('mentors')->select(['first_name', 'last_name'])->orderBy('id', 'desc')->where([
//     'age' => 30
// ])->where(fn($builder) => $builder->where('name', '=', 'pipe'))->get();

// echo Builder::table('mentors')->select(['first', 'second'])->whereIn('id', [1, 2, 3])->orWhere(function($builder) {
//     $builder->where('name', '=', 'pipe')->where('age', '=', 30)->orWhere('age', '=', 40);
// })->get();

echo User::execute()->select(['first_name', 'last_name'])->where('id', '=', 1)->get();

// echo MySQLBuilder::table('samples')->where('id', '=',2)->join('samples', 'samples.id', '=', 'samples.id')->get();