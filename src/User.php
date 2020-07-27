<?php

declare(strict_types=1);

namespace App;

final class User
{
    public string $id;
    public string $login;
    public string $passwordHash;
    public string $email;
    public string $name;

    public function __construct(string $id, string $login, string $passwordHash, string $email, string $name)
    {
        $this->id = $id;
        $this->login = $login;
        $this->passwordHash = $passwordHash;
        $this->email = $email;
        $this->name = $name;
    }
}