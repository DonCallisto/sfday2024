<?php

namespace App\Shared\Infrastructure\Message\ACL\League;

use App\League\Domain\Event\Participant\DisableParticipant;
use App\Shared\Domain\Event\EventDispatcherInterface;
use App\User\Domain\Event\UserSuspendend;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

/*
 * !! 100% of decoupling is something just not feasible. This section of code can be implemented in a lot of way. Maybe
 * like an Anti-Corruption Layer or a proxy or something like that. For the principle of "keep pipes dumb, end-points smart"
 * and for simplicity, we are going to implement this as a message handler which translates a message from one context to another. !!
 */
#[AsMessageHandler]
final class FromUserSuspendedToDisableParticipant
{
    public function __construct(private readonly EventDispatcherInterface $eventDispatcher)
    {
    }

    public function __invoke(UserSuspendend $userSuspendend): void
    {
        $this->eventDispatcher->dispatch(new DisableParticipant($userSuspendend->suspendedUserId));
    }
}