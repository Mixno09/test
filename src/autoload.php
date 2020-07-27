<?php

require_once __DIR__ . '/function.php';

spl_autoload_register(function ($className) {
    if (strpos($className, 'App\\') === 0) {
        $className = mb_substr($className, 4);
        require_once __DIR__ . '/' . $className . '.php';
    }
});