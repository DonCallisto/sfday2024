<?php

namespace App\User\Application\Service;

final readonly class UserSuspendedData
{
    public function __construct(
        public string $userID,
        public string $reason,
        public string $suspendedByUsername,
        public \DateTimeInterface $suspendedTill
    ) {
    }
}