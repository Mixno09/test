<?php

use App\AuthenticationService;

$errors = [];

$rules = [
    'login' => [
        'filter' => FILTER_CALLBACK,
        'options' => function ($value) use (&$errors) {
            if (! is_string($value)) {
                $errors[] = 'Логин должен быть строкой';
                return null;
            }
            $value = trim($value);
            if ($value === '') {
                $errors[] = 'Логин должен быть заполнен';
                return null;
            }
            return $value;
        },
    ],
    'password' => [
        'filter' => FILTER_CALLBACK,
        'options' => function ($value) use (&$errors) {
            if (! is_string($value)) {
                $errors[] = 'Пароль должен быть строкой';
                return null;
            }
            if ($value === '') {
                $errors[] = 'Пароль должен быть заполнен';
                return null;
            }
            return $value;
        },
    ],
];
$data = filter_input_array(INPUT_POST, $rules);
if (count($errors) > 0) {
    header('Content-Type: application/json', true, 422);
    echo json_encode($errors, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE);
    return;
}

$authenticationService = new AuthenticationService();

$result = $authenticationService->login($data['login'], $data['password']);

if (! $result) {
    header('Content-Type: application/json', true, 422);
    $errors[] = 'Комбинация логин-пароль не найдена';
    echo json_encode($errors, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE);
    return;
}

header('Content-Type: application/json');
echo json_encode([], JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE);