<?php

namespace App\User\Infrastructure\Message\MessageHandler\Symfony;

use App\User\Application\Service\UserSuspensionRevokedHandler;
use App\User\Domain\Event\UserSuspensionRevoked;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class UserSuspensionRevokedMessageHandler
{
    public function __construct(private readonly UserSuspensionRevokedHandler $userSuspensionRevokedHandler)
    {
    }

    public function __invoke(UserSuspensionRevoked $userSuspensionRevoked): void
    {
        // !! At the moment this isn't working because the internal mailer isn't configured !!
        $this->userSuspensionRevokedHandler->execute($userSuspensionRevoked->userId);
    }
}