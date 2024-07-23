<?php

namespace App\User\Application\Event;

final readonly class UserCreatedViaCLI
{
    public function __construct(public string $userId, public string $email, public bool $isSuperAdmin)
    {
    }
}