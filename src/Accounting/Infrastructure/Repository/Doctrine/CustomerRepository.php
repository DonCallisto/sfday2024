<?php

namespace App\Accounting\Infrastructure\Repository\Doctrine;

use App\Accounting\Domain\Model\Customer\Customer;
use App\Accounting\Domain\Model\Customer\CustomerID;
use App\Accounting\Domain\Repository\CustomerRepositoryInterface;
use App\Accounting\Domain\Repository\Exception\CustomerNotFoundException;
use Doctrine\ORM\EntityManagerInterface;

final class CustomerRepository implements CustomerRepositoryInterface
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    public function save(Customer $customer): void
    {
        $this->entityManager->persist($customer);
        $this->entityManager->flush();
    }

    public function findByID(CustomerID $id): Customer
    {
        $customer = $this->entityManager
            ->createQueryBuilder()
            ->select('c')
            ->from(Customer::class, 'c')
            ->where('c.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();

        if (!$customer) {
            CustomerNotFoundException::withIdentifier($id);
        }

        return $customer;
    }
}