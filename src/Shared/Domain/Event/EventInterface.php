<?php

namespace App\Shared\Domain\Event;

interface EventInterface
{
    public function isSchedulable(): bool;

    public function scheduleAt(): ?\DateTimeInterface;
}