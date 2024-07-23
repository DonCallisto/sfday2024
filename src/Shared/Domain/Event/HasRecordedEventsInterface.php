<?php

namespace App\Shared\Domain\Event;

interface HasRecordedEventsInterface
{
    /**
     * !! See Noback's book !!
     *
     * @return list<EventInterface>
     */
    public function recordedEvents(): array;
}