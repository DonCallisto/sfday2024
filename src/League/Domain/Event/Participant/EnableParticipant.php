<?php

namespace App\League\Domain\Event\Participant;

use App\Shared\Domain\Event\EventInterface;

final readonly class EnableParticipant implements EventInterface
{
    public function __construct(public string $participantID)
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