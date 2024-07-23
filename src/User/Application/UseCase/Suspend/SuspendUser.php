<?php

namespace App\User\Application\UseCase\Suspend;

use App\User\Application\DTO\SuspendUserData;
use App\User\Domain\Model\Exception\SuspensionException;
use App\User\Domain\Model\User\UserID;
use App\User\Domain\Repository\UserRepositoryInterface;
use App\User\Domain\UseCase\Suspend\SuspendUser as SuspendUserDomainUseCase;
use Symfony\Component\Uid\Uuid;

final class SuspendUser
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly SuspendUserDomainUseCase $suspendUser
    ) {
    }

    /**
     * @throws SuspensionException
     */
    public function suspend(string $userSuspendedByID, string $userToBeSuspendedID, SuspendUserData $data): void
    {
        $suspendedBy = $this->userRepository->findById(new UserID(Uuid::fromString($userSuspendedByID)));

        if (!$suspendedBy->isSuperAdmin()) {
            throw new SuspensionException('Users can be suspended only by super admins');
        }

        $userToBeSuspended = $this->userRepository->findById(new UserID(Uuid::fromString($userToBeSuspendedID)));

        $this->suspendUser->suspend(
            $suspendedBy,
            $userToBeSuspended,
            $data->suspendedTill ?? throw new \LogicException('Suspended till is required'),
            $data->reason
        );
    }

    /**
     * @throws SuspensionException
     */
    public function revokeSuspension(string $revokedByUserID, string $userToRevokeSuspensionID): void
    {
        $revokedBy = $this->userRepository->findById(new UserID(Uuid::fromString($revokedByUserID)));

        /*
         * !! This control, even is duplicated, is necessary as we don't want to "trust" the client for this kind of checks.
         * The fact that, at the moment, this use case is invoked only within the context of a specific execution path, doesn't
         * means it will be in the future. Having said that, I would not consider this control as a real duplication. The one
         * made at access management level is something useful for UX experience and to avoid unnecessary calls to the use case. !!
         */
        if (!$revokedBy->isSuperAdmin()) {
            throw SuspensionException::notRevokedBySuperAdmin();
        }

        $userToRevokeSuspension = $this->userRepository->findById(new UserID(Uuid::fromString($userToRevokeSuspensionID)));

        $this->suspendUser->revokeSuspension($userToRevokeSuspension);
    }
}