<?php

namespace App\User\Infrastructure\Repository\Doctrine;

use App\User\Application\Repository\UserByIdentifierRepositoryInterface;
use App\User\Domain\Model\User\User;
use App\User\Domain\Model\User\UserID;
use App\User\Domain\Repository\Exception\UserNotFoundException;
use App\User\Domain\Repository\UserRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Uid\Uuid;

final class UserRepository implements UserRepositoryInterface, UserByIdentifierRepositoryInterface
{
    public function __construct(private readonly EntityManagerInterface $em)
    {
    }

    public function nextId(): UserID
    {
        return new UserID(Uuid::v4());
    }

    public function findAll(): array
    {
        return $this->em->createQueryBuilder()
            ->select('u')
            ->from(User::class, 'u')
            ->getQuery()
            ->getResult();
    }

    public function findById(UserID $id): User
    {
        $user = $this->em->createQueryBuilder()
            ->select('u')
            ->from(User::class, 'u')
            ->where('u.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();

        if (!$user) {
            throw UserNotFoundException::withId($id);
        }

        return $user;
    }

    public function findByIdentifier(string $identifier): User
    {
        $user = $this->findByIdentifierQB($identifier)
            ->select('u')
            ->getQuery()
            ->getOneOrNullResult();

        if (!$user) {
            throw UserNotFoundException::withIdentifier($identifier);
        }

        return $user;
    }

    private function findByIdentifierQB(string $identifier): QueryBuilder
    {
        return $this->em->createQueryBuilder()
            ->from(User::class, 'u')
            ->where('u.username = :identifier')
            ->setParameter('identifier', $identifier);
    }

    public function findUserIDByIdentifier(string $identifier): UserID
    {
        $result = $this->findByIdentifierQB($identifier)
            ->select('u.id')
            ->getQuery()
            ->getOneOrNullResult();

        if (!$result) {
            throw UserNotFoundException::withIdentifier($identifier);
        }

        return $result['id'];
    }

    public function save(User $user): void
    {
        $this->em->persist($user);
        $this->em->flush();
    }

    public function userExists(string $username, string $email): bool
    {
        return (bool) $this->em->createQueryBuilder()
            ->select('COUNT(u.id)')
            ->from(User::class, 'u')
            ->where('u.username = :username')
            ->orWhere('u.email = :email')
            ->setParameter('username', $username)
            ->setParameter('email', $email)
            ->getQuery()
            ->getSingleScalarResult();
    }
}