<?php

namespace App\User\Domain\Event;

use App\Shared\Domain\Event\EventInterface;

final readonly class UserSuspendend implements EventInterface
{
    public \DateTimeImmutable $suspendedTill;

    /*
     * !! Is it better to have all base types in order to avoid serializations of objects which can be difficult to manage
     * when object structure changes (e.g. renaming properties) !!
     */
    public function __construct(
        public string $suspendedUserId,
        public string $suspendedByUserIdentifier,
        \DateTimeInterface $suspendedTill,
        public string $reason
    ) {
        $this->suspendedTill = \DateTimeImmutable::createFromInterface($suspendedTill);
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