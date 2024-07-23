<?php

namespace App\User\Application\API\Model;

final readonly class UserDetailModel
{
    public function __construct(public string $userID, public string $username, public bool $isSuspended)
    {
    }
}