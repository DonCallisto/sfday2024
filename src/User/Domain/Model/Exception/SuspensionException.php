<?php

namespace App\User\Domain\Model\Exception;

class SuspensionException extends UserException
{
    public static function userIsSuperAdmin(): self
    {
        return new self('Cannot suspend super admin');
    }

    public static function suspensionInPast(): self
    {
        return new self('Suspension date cannot be in the past');
    }

    public static function userIsAlreadySuspended(): self
    {
        return new self('User is already suspended');
    }

    public static function notRevokedBySuperAdmin(): self
    {
        return new self('Only super admins can revoke suspensions');
    }

    public static function revokedOnNotSuspendedUser(): self
    {
        return new self('Cannot revoke suspension on not suspended user');
    }
}