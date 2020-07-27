<?php

use App\UserRepository;
use App\User;

$repository = new UserRepository();
$errors = [];

$rules = [
    'login' => [
        'filter' => FILTER_CALLBACK,
        'options' => function ($value) use (&$errors, $repository) {
            if (! is_string($value)) {
                $errors[] = 'Логин должен быть строкой';
                return null;
            }
            $value = trim($value);
            if ($value === '') {
                $errors[] = 'Логин должен быть заполнен';
                return null;
            }
            $user = $repository->findByLogin($value);
            if ($user instanceof User) {
                $errors[] = 'Пользователь с таким логином существует';
                return null;
            }
            return $value;
        },
    ],
    'name' => [
        'filter' => FILTER_CALLBACK,
        'options' => function ($value) use (&$errors) {
            if (! is_string($value)) {
                $errors[] = 'Имя должно быть строкой';
                return null;
            }
            $value = trim($value);
            if ($value === '') {
                $errors[] = 'Имя должно быть заполнено';
                return null;
            }
            return $value;
        },
    ],
    'email' => [
        'filter' => FILTER_CALLBACK,
        'options' => function ($value) use (&$errors, $repository) {
            if (! is_string($value)) {
                $errors[] = 'Email должен быть строкой';
                return null;
            }
            $value = trim($value);
            $value = filter_var($value, FILTER_VALIDATE_EMAIL);
            if (! $value) {
                $errors[] = 'Неправильный формат Email';
                return null;
            }
            $user = $repository->findByEmail($value);
            if ($user instanceof User) {
                $errors[] = 'Пользователь с таким Email существует';
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

$id = $repository->nextId();
$passwordHash = password_hash($data['password'], PASSWORD_BCRYPT);
$user = new User($id, $data['login'], $passwordHash, $data['email'], $data['name']);
$repository->save($user);

header('Content-Type: application/json');
echo json_encode([], JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE);
