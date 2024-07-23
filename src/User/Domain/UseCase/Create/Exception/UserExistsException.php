<?php

namespace App\User\Domain\UseCase\Create\Exception;

use App\User\Domain\Model\Exception\UserException;

final class UserExistsException extends UserException
{
    public static function userExists(string $username, string $email): self
    {
        return new self(sprintf('User with username %s or email %s already exists', $username, $email));
    }
}