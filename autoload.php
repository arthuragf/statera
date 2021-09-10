<?php
spl_autoload_register(function($sClass){
    $sClass = str_replace('statera', __DIR__, $sClass);
    $sFile = str_replace('\\', DIRECTORY_SEPARATOR, $sClass) . '.class.php';

    if (file_exists($sFile)) {
        require $sFile;
    }
});