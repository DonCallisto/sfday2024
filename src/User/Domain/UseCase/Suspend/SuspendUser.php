<?php

namespace App\User\Domain\UseCase\Suspend;

use App\Shared\Domain\Event\EventDispatcherInterface;
use App\Shared\Domain\Event\HasEventsToDispatchTrait;
use App\Shared\Domain\Utils\DateTime\DateTimeProviderInterface;
use App\User\Domain\Model\Exception\SuspensionException;
use App\User\Domain\Model\User\User;
use App\User\Domain\Repository\UserRepositoryInterface;

final class SuspendUser
{
    use HasEventsToDispatchTrait;

    public function __construct(
        private readonly DateTimeProviderInterface $dateTimeProvider,
        private readonly UserRepositoryInterface $userRepository,
        private readonly EventDispatcherInterface $eventDispatcher
    ) {
    }

    /**
     * @throws SuspensionException
     */
    public function suspend(User $suspendedBy, User $userToBeSuspended, \DateTimeInterface $suspendedTill, string $reason): void
    {
        $userToBeSuspended->suspend($suspendedBy, $this->dateTimeProvider->now(), $suspendedTill, $reason);
        $this->userRepository->save($userToBeSuspended);

        $this->dispatchEvents($userToBeSuspended, $this->eventDispatcher);
    }

    /**
     * @throws SuspensionException
     */
    public function revokeSuspension(User $userToRevokeSuspension): void
    {
        $userToRevokeSuspension->revokeSuspension();
        $this->userRepository->save($userToRevokeSuspension);

        $this->dispatchEvents($userToRevokeSuspension, $this->eventDispatcher);
    }
}