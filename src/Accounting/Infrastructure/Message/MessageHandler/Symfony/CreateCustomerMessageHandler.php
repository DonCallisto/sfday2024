<?php

namespace App\Accounting\Infrastructure\Message\MessageHandler\Symfony;

use App\Accounting\Application\Event\CreateCustomer;
use App\Accounting\Application\Service\CreateCustomerData;
use App\Accounting\Application\Service\CreateCustomerHandler;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class CreateCustomerMessageHandler
{
    public function __construct(private readonly CreateCustomerHandler $createCustomerHandler)
    {
    }

    public function __invoke(CreateCustomer $createCustomer): void
    {
        $this->createCustomerHandler->execute(new CreateCustomerData(
            $createCustomer->customerId,
            $createCustomer->name,
            $createCustomer->surname,
            $createCustomer->email
        ));
    }
}