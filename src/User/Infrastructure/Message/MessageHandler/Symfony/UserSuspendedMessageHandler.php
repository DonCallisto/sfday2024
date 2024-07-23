<?php

namespace App\User\Infrastructure\Message\MessageHandler\Symfony;

use App\User\Application\Service\UserSuspendedData;
use App\User\Application\Service\UserSuspendedHandler;
use App\User\Domain\Event\UserSuspendend;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class UserSuspendedMessageHandler
{
    public function __construct(private readonly UserSuspendedHandler $userSuspendedHandler)
    {
    }

    public function __invoke(UserSuspendend $userSuspended): void
    {
        // !! At the moment this isn't working because the internal mailer isn't configured !!
        $this->userSuspendedHandler->execute(new UserSuspendedData(
            $userSuspended->suspendedUserId,
            $userSuspended->reason,
            $userSuspended->suspendedByUserIdentifier,
            $userSuspended->suspendedTill,
        ));
    }
}