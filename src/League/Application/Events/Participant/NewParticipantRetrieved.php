<?php

namespace App\League\Application\Events\Participant;

use App\Shared\Domain\Event\EventInterface;

final readonly class NewParticipantRetrieved implements EventInterface
{
    public function __construct(public string $id, public string $username, public bool $isSuspended)
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