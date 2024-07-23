<?php

namespace App\User\Infrastructure\Message\MessageHandler\Symfony;

use App\User\Domain\Event\RevokeUserSuspension;
use App\User\Domain\Model\User\UserID;
use App\User\Domain\Repository\UserRepositoryInterface;
use App\User\Domain\UseCase\Suspend\SuspendUser;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Uid\Uuid;

#[AsMessageHandler]
final class RevokeUserSuspensionMessageHandler
{
    public function __construct(private readonly SuspendUser $suspendUser, private readonly UserRepositoryInterface $userRepository)
    {
    }

    public function __invoke(RevokeUserSuspension $revokeUserSuspension): void
    {
        $this->suspendUser->revokeSuspension($this->userRepository->findById(new UserID(Uuid::fromString($revokeUserSuspension->userId))));
    }
}