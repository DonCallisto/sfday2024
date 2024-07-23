<?php

namespace App\Accounting\Application\Service;

final readonly class CreateCustomerData
{
    public function __construct(
        public string $customerId,
        public string $name,
        public string $surname,
        public string $email,
    ) {
    }
}