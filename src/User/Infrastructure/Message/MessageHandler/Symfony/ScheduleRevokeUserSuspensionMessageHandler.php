<?php

namespace App\User\Infrastructure\Message\MessageHandler\Symfony;

use App\Shared\Domain\Event\EventDispatcherInterface;
use App\User\Domain\Event\RevokeUserSuspension;
use App\User\Domain\Event\ScheduleRevokeUserSuspension;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class ScheduleRevokeUserSuspensionMessageHandler
{
    public function __construct(private readonly EventDispatcherInterface $eventDispatcher)
    {
    }

    public function __invoke(ScheduleRevokeUserSuspension $scheduleRevokeUserSuspension): void
    {
        $this->eventDispatcher->dispatch(new RevokeUserSuspension($scheduleRevokeUserSuspension->userId));
    }
}