<?php

namespace App\League\Domain\Model\League\Exception;

final class EmptyPasswordException extends LeagueException
{
    public static function emptyPassword(): self
    {
        return new self('Password is required for private leagues');
    }
}