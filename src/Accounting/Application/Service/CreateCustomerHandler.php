<?php

namespace App\Accounting\Application\Service;

use App\Accounting\Domain\Model\Customer\Customer;
use App\Accounting\Domain\Model\Customer\CustomerID;
use App\Accounting\Domain\Repository\CustomerRepositoryInterface;
use Symfony\Component\Uid\Uuid;

final class CreateCustomerHandler
{
    public function __construct(private readonly CustomerRepositoryInterface $customerRepository)
    {
    }

    public function execute(CreateCustomerData $data): void
    {
        $customer = new Customer(
            new CustomerID(Uuid::fromString($data->customerId)),
            $data->name,
            $data->surname,
            $data->email
        );

        $this->customerRepository->save($customer);
    }
}