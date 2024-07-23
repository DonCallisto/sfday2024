<?php

namespace App\Accounting\Infrastructure\DB\Doctrine\Type;

use App\Accounting\Domain\Model\Customer\CustomerID;
use App\Shared\Infrastructure\Doctrine\Type\BaseIDType;

final class CustomerIDType extends BaseIDType
{
    protected function getIDFQCN(): string
    {
        return CustomerID::class;
    }

    public function getName(): string
    {
        return 'customer_id';
    }
}