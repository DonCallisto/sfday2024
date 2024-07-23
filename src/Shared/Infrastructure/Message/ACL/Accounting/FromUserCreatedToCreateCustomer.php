<?php

namespace App\Shared\Infrastructure\Message\ACL\Accounting;

use App\Accounting\Application\Event\CreateCustomer;
use App\Shared\Domain\Event\EventDispatcherInterface;
use App\User\Domain\Event\UserCreated;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class FromUserCreatedToCreateCustomer
{
    public function __construct(private readonly EventDispatcherInterface $eventDispatcher)
    {
    }

    public function __invoke(UserCreated $userCreated): void
    {
        $this->eventDispatcher->dispatch(new CreateCustomer(
            $userCreated->userId,
            $userCreated->name,
            $userCreated->surname,
            $userCreated->email
        ));
    }
}