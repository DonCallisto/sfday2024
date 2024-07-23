<?php

namespace App\Shared\Infrastructure\Message\ACL\League;

use App\League\Domain\Event\Participant\EnableParticipant;
use App\Shared\Domain\Event\EventDispatcherInterface;
use App\User\Domain\Event\UserSuspensionRevoked;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class FromUserSuspensionRevokedToEnableParticipant
{
    public function __construct(private readonly EventDispatcherInterface $eventDispatcher)
    {
    }

    public function __invoke(UserSuspensionRevoked $revokeSuspension): void
    {
        $this->eventDispatcher->dispatch(new EnableParticipant($revokeSuspension->userId));
    }
}