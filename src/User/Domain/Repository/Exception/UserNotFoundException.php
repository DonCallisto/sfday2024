<?php

namespace App\User\Domain\Repository\Exception;

use App\User\Domain\Model\User\UserID;

final class UserNotFoundException extends \RuntimeException
{
    public static function withId(UserID $id): self
    {
        return new self(sprintf('User with id %s not found', $id->toString()));
    }

    public static function withIdentifier(string $identifier): self
    {
        return new self(sprintf('User with identifier %s not found', $identifier));
    }
}