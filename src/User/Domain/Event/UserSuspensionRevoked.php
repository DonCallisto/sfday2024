<?php

namespace App\User\Domain\Event;

use App\Shared\Domain\Event\EventInterface;

final readonly class UserSuspensionRevoked implements EventInterface
{
    public function __construct(public string $userId)
    {
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