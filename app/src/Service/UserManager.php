<?php

namespace App\Service;

use App\Entity\User;
use App\Interface\UserManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserManager implements UserManagerInterface
{

    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher,
    )
    {
    }

    public function processNewUser(User $user, ?string $plainPassword): void
    {
        $this->hashPassword($user, $plainPassword);
    }

    private function hashPassword(User $user, ?string $plainPassword): void
    {
        $hashedPassword = $this->passwordHasher->hashPassword($user, $plainPassword);
        $user->setPassword($hashedPassword);
    }
}