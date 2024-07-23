<?php

namespace App\Accounting\Application\Api;

use App\Accounting\Application\Api\Model\CustomerLeaguesModel;
use App\Accounting\Domain\Model\Customer\CustomerID;
use App\Accounting\Domain\Repository\CustomerRepositoryInterface;
use App\Accounting\Domain\Repository\Exception\CustomerNotFoundException;
use Symfony\Component\Uid\Uuid;

final class CustomerLeagues
{
    public function __construct(private readonly CustomerRepositoryInterface $customerRepository)
    {
    }

    public function get(string $id): ?CustomerLeaguesModel
    {
        try {
            // !! There's no need for the whole object. We can extract just the plan. Done for simplicity of the example. !!
            $user = $this->customerRepository->findByID(new CustomerID(Uuid::fromString($id)));
        } catch (CustomerNotFoundException) {
            return null;
        }

        if ($user->hasFreePlan()) {
            return new CustomerLeaguesModel(1, 1);
        }

        if ($user->hasBasicPlan()) {
            return new CustomerLeaguesModel(1, 3);
        }

        if ($user->hasPremiumPlan()) {
            return new CustomerLeaguesModel(3, 5);
        }

        return new CustomerLeaguesModel(null, null);
    }
}