<?php

namespace App\Accounting\Domain\Repository\Exception;

use App\Accounting\Domain\Model\Customer\CustomerID;

final class CustomerNotFoundException extends \RuntimeException
{
    public static function withIdentifier(CustomerID $id): self
    {
        return new self(sprintf('Customer with id %s not found', $id));
    }
}