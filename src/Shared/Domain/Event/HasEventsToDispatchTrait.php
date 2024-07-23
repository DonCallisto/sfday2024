<?php

namespace App\Shared\Domain\Event;

use App\Shared\Domain\Event\Exception\DispatchException;

trait HasEventsToDispatchTrait
{

    private function dispatchEvents(HasRecordedEventsInterface $subject, EventDispatcherInterface $dispatcher): void
    {
        foreach ($subject->recordedEvents() as $event) {
            try {
                $dispatcher->dispatch($event);
            } catch (DispatchException) {
                // Save event for a retry or whatever
            }
        }
    }
}