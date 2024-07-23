<?php

namespace App\User\Application\UseCase\Promote;

use App\Shared\Domain\Event\EventDispatcherInterface;
use App\Shared\Domain\Event\HasEventsToDispatchTrait;
use App\User\Domain\Model\Exception\PromitionException;
use App\User\Domain\Model\User\UserID;
use App\User\Domain\Repository\UserRepositoryInterface;
use App\User\Domain\UseCase\Promote\PromoteUser as PromoteUserDomainUseCase;
use Symfony\Component\Uid\Uuid;

final class PromoteUser
{
    use HasEventsToDispatchTrait;

    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly EventDispatcherInterface $eventDispatcher,
        private readonly PromoteUserDomainUseCase $promoteUser
    ) {
    }

    /**
     * @throws PromitionException
     */
    public function execute(string $userPromoterID, string $userToBePromotedID): void
    {
        $promoter = $this->userRepository->findById(new UserID(Uuid::fromString($userPromoterID)));
        $toBePromoted = $this->userRepository->findById(new UserID(Uuid::fromString($userToBePromotedID)));

        /*
         * !! This control, even is duplicated, is necessary as we don't want to "trust" the client for this kind of checks.
         * The fact that, at the moment, this use case is invoked only within the context of a specific execution path, doesn't
         * means it will be in the future. Having said that, I would not consider this control as a real duplication. The one
         * made at access management level is something useful for UX experience and to avoid unnecessary calls to the use case. !!
         */
        if (!$promoter->isSuperAdmin()) {
            throw new PromitionException('Only super admins can promote users');
        }

        $this->promoteUser->execute($toBePromoted);
    }
}