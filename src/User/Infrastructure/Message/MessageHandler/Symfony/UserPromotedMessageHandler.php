<?php

namespace App\User\Infrastructure\Message\MessageHandler\Symfony;

use App\User\Domain\Event\UserPromoted;
use App\User\Domain\Model\User\UserID;
use App\User\Domain\Repository\Exception\UserNotFoundException;
use App\User\Domain\Repository\UserRepositoryInterface;
use App\User\Infrastructure\Model\User\Symfony\SymfonyUserRole;
use App\User\Infrastructure\Model\User\Symfony\SymfonyUserRoleID;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Uid\Uuid;

#[AsMessageHandler]
final class UserPromotedMessageHandler
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly EntityManagerInterface $em
    ) {
    }

    public function __invoke(UserPromoted $userPromoted): void
    {
        try {
            $user = $this->userRepository->findById(new UserID(Uuid::fromString($userPromoted->userID)));
        } catch (UserNotFoundException) {
            return;
        }

        if (!$user->isSuperAdmin()) {
            return;
        }

        $userRole = $this->getSymfonyRolesEntity(new UserID(Uuid::fromString($userPromoted->userID)));
        if (!$userRole) {
            $userRole = new SymfonyUserRole(new SymfonyUserRoleID(Uuid::v4()), $user, ['ROLE_USER']);
        }

        $userRole->addRole('ROLE_SUPER_ADMIN');
        $this->em->persist($userRole);
        $this->em->flush();
    }

    private function getSymfonyRolesEntity(UserID $userID): ?SymfonyUserRole
    {
        return $this->em->createQueryBuilder()
            ->select('sur')
            ->from(SymfonyUserRole::class, 'sur')
            ->innerJoin('sur.user', 'u')
            ->where('u.id = :userId')
            ->setParameter('userId', $userID)
            ->getQuery()
            ->getOneOrNullResult();
    }
}