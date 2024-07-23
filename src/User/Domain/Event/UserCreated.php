<?php

namespace App\User\Domain\Event;

use App\Shared\Domain\Event\EventInterface;

final readonly class UserCreated implements EventInterface
{
    public function __construct(
        public string $userId,
        public string $name,
        public string $surname,
        public string $username,
        public string $email,
    ) {
    }

    public function isSchedulable(): false
    {
        return false;
    }

    public function scheduleAt(): null
    {
        return null;
    }
}