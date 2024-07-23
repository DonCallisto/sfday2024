<?php

namespace App\User\Infrastructure\Authentication\Symfony\UserProvider;

use App\User\Domain\Model\User\User;
use App\User\Infrastructure\Model\User\Symfony\SymfonyUser;
use App\User\Infrastructure\Model\User\Symfony\SymfonyUserRole;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

// @todo si potrebbe fare con un UserLoaderInterface? studiare poi
final class UserProvider implements UserProviderInterface, PasswordUpgraderInterface
{
    public function __construct(private readonly EntityManagerInterface $em)
    {
    }

    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        $userData = $this->em->createQueryBuilder()
            ->select('u.id, u.username, u.email, u.password, r.roles')
            ->from(User::class, 'u')
            ->leftJoin(SymfonyUserRole::class, 'r', 'WITH', 'u.id = r.user')
            ->where('u.username = :identifier')
            ->setParameter('identifier', $identifier)
            ->getQuery()
            ->getResult();

        if (empty($userData)) {
            $exception = new UserNotFoundException();
            $exception->setUserIdentifier($identifier);

            throw $exception;
        }

        $userData = array_shift($userData);

        return new SymfonyUser($userData['id'], $userData['username'], $userData['password'], $userData['roles']);
    }

    public function refreshUser(UserInterface $user): UserInterface
    {
        return $this->loadUserByIdentifier($user->getUserIdentifier());
    }

    public function supportsClass(string $class): bool
    {
        return SymfonyUser::class === $class;
    }

    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        // @todo implement with an event, if interested in this situation
    }
}