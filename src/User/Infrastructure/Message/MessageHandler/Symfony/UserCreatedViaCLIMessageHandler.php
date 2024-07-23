<?php

namespace App\User\Infrastructure\Message\MessageHandler\Symfony;

use App\User\Application\Event\UserCreatedViaCLI;
use App\User\Domain\Model\User\UserID;
use App\User\Domain\Repository\UserRepositoryInterface;
use App\User\Infrastructure\Model\User\Symfony\SymfonyUserRole;
use App\User\Infrastructure\Model\User\Symfony\SymfonyUserRoleID;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Uid\Uuid;

/*
 * !! This handler, compared to the others, contains directly a certain logic. That's because it would not have any sense
 * to create a service for infrastructure related logic !!
 */
#[AsMessageHandler]
final class UserCreatedViaCLIMessageHandler
{
    public function __construct(private readonly UserRepositoryInterface $userRepository, private readonly EntityManagerInterface $em)
    {
    }

    public function __invoke(UserCreatedViaCLI $userCreatedViaCLI): void
    {
        $roles = ['ROLE_USER'];
        if ($userCreatedViaCLI->isSuperAdmin) {
            $roles[] = 'ROLE_SUPER_ADMIN';
        }

        $user = $this->userRepository->findById(new UserID(Uuid::fromString($userCreatedViaCLI->userId)));

        $userRoles = new SymfonyUserRole(new SymfonyUserRoleID(Uuid::v4()), $user, $roles);
        $this->em->persist($userRoles);
        $this->em->flush();
    }
}