<?php
function classLoader_($class)
{
    $path = str_replace('\\', DIRECTORY_SEPARATOR, $class);
    $file = __DIR__ . '/src/' . $path . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
}
spl_autoload_register('classLoader_');
