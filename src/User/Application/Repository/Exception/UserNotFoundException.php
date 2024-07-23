<?php

namespace App\User\Application\Repository\Exception;

final class UserNotFoundException extends \RuntimeException
{
    public static function withIdentifier(string $identifier): self
    {
        return new self(sprintf('User with identifier %s not found', $identifier));
    }
}