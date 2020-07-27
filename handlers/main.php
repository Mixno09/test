<?php

use App\AuthenticationService;
use function App\render;

$authService = new AuthenticationService();
$user = $authService->user();

echo render('main', ['user' => $user]);