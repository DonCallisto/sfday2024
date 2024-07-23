<?php

namespace App\User\Domain\UseCase\Promote;

use App\Shared\Domain\Event\EventDispatcherInterface;
use App\Shared\Domain\Event\HasEventsToDispatchTrait;
use App\User\Domain\Model\Exception\PromitionException;
use App\User\Domain\Model\User\User;
use App\User\Domain\Repository\UserRepositoryInterface;

final class PromoteUser
{
    use HasEventsToDispatchTrait;

    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly EventDispatcherInterface $eventDispatcher
    ) {
    }

    /**
     * @throws PromitionException
     */
    public function execute(User $toBePromoted): void
    {
        $toBePromoted->promote();
        $this->userRepository->save($toBePromoted);
        $this->dispatchEvents($toBePromoted, $this->eventDispatcher);
    }
}