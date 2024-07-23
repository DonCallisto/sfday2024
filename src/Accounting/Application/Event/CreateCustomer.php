<?php

namespace App\Accounting\Application\Event;

use App\Shared\Domain\Event\EventInterface;

final readonly class CreateCustomer implements EventInterface
{
    public function __construct(
        public string $customerId,
        public string $name,
        public string $surname,
        public string $email,
    ) {
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