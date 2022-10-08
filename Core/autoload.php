<?php

spl_autoload_register(/**
 * @throws Exception
 */ function ($filename) {
    $file = ROOT."/".$filename.'.php';

    $file = str_replace("\\", '/', $file);

    if(file_exists($file)){
        require_once $file;
    }else{
        throw new Exception('file'.$file.'not found');
    }
});
