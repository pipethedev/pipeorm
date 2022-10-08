<?php

$root = dirname(__FILE__);

$root = str_replace("\\", '/', $root);

define('ROOT', $root);
require_once ROOT."/Core/autoload.php";
require_once ROOT."/Core/helpers.php";
