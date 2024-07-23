<?php

namespace App\User\Domain\Model\Exception;

final class PromitionException extends UserException
{
    public static function userIsSupended(): self
    {
        return new self('Cannot promote suspended user');
    }
}