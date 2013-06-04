<?php
function __autoload($class)
{
    $file_name = (__DIR__) . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';
    if(file_exists($file_name)) {
        require_once($file_name);
    }
}
?>