<?php

namespace App\Accounting\Domain\Repository;

use App\Accounting\Domain\Model\Customer\Customer;
use App\Accounting\Domain\Model\Customer\CustomerID;
use App\Accounting\Domain\Repository\Exception\CustomerNotFoundException;

interface CustomerRepositoryInterface
{
    /**
     * @throws CustomerNotFoundException
     */
    public function findByID(CustomerID $id): ?Customer;

    public function save(Customer $customer): void;
}