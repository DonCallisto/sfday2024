<?php

namespace App\Shared\Infrastructure\Message\MessageBus\Symfony;

use App\Shared\Domain\Event\EventDispatcherInterface;
use App\Shared\Domain\Event\EventInterface;
use App\Shared\Domain\Event\Exception\DispatchException;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\DelayStamp;

final class DefaultMessageBus implements EventDispatcherInterface
{
    public function __construct(readonly MessageBusInterface $messageBus)
    {
    }

    public function dispatch(EventInterface $message): Envelope
    {
        $stamps = [];
        if ($message->isSchedulable()) {
            $stamps[] = DelayStamp::delayUntil($message->scheduleAt());
        }

        try {
            return $this->messageBus->dispatch($message, $stamps);
        } catch (\Throwable $e) {
            throw new DispatchException($e->getMessage(), $e->getCode(), $e);
        }
    }
}