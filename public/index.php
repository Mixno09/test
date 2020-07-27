<?php

require_once __DIR__ . '/../src/autoload.php';

(function () {
    $handler = 'not_found';

    $method = $_SERVER['REQUEST_METHOD'];
    $uri = $_SERVER['REQUEST_URI'];
    if ($uri === '/registration' && $method === 'GET') {
        $handler = 'registration_form';
    } elseif ($uri === '/registration' && $method === 'POST') {
        $handler = 'registration';
    } elseif ($uri === '/login' && $method === 'GET') {
        $handler = 'login_form';
    } elseif ($uri === '/login' && $method === 'POST') {
        $handler = 'login';
    } elseif ($uri === '/' && $method === 'GET') {
        $handler = 'main';
    }

    require __DIR__ . "/../handlers/{$handler}.php";
})();


