<?php

namespace App\User\Domain\Event;

use App\Shared\Domain\Event\EventInterface;

final readonly class UserPromoted implements EventInterface
{
    public function __construct(public string $userID)
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