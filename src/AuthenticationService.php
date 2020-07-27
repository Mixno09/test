<?php

declare(strict_types=1);

namespace App;

final class AuthenticationService
{
    private UserRepository $userRepository;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
        session_start();
    }

    public function login(string $login, string $password): bool
    {
        $user = $this->userRepository->findByLogin($login);
        if (! $user instanceof User) {
            return false;
        }
        if (! password_verify($password, $user->passwordHash)) {
            return false;
        }

        $_SESSION['user'] = $user->id;

        return true;
    }

    public function user(): ?User
    {
        $id = $_SESSION['user'] ?? null;
        if (! is_string($id)) {
            return null;
        }
        return $this->userRepository->findById($id);
    }
}