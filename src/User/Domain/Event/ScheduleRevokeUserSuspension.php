<?php

namespace App\User\Domain\Event;

use App\Shared\Domain\Event\EventInterface;

final readonly class ScheduleRevokeUserSuspension implements EventInterface
{
    public \DateTimeImmutable $when;

    public function __construct(public string $userId, \DateTimeInterface $when)
    {
        $this->when = \DateTimeImmutable::createFromInterface($when);
    }

    public function isSchedulable(): true
    {
        return true;
    }

    public function scheduleAt(): \DateTimeImmutable
    {
        return $this->when;
    }
}