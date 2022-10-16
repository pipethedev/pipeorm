<?php

spl_autoload_register(/**
 * @throws Exception
 */ function ($filename) {
    $file = ROOT."/".$filename.'.php';

    $file = str_replace("\\", '/', $file);

    file_exists($file) ? require_once $file : printf('file'.$file.'not found');
});
