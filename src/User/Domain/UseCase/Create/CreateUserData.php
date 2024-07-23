<?php

namespace App\User\Domain\UseCase\Create;

use App\User\Domain\Model\User\UserID;

final readonly class CreateUserData
{
    public function __construct(
        public UserID $userId,
        public string $name,
        public string $surname,
        public string $username,
        public string $email,
        public string $hashedPassword,
        public bool $isSuperAdmin
    ) {
    }
}