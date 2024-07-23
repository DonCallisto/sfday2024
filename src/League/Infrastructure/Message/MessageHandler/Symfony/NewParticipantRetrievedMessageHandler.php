<?php

namespace App\League\Infrastructure\Message\MessageHandler\Symfony;

use App\League\Application\Events\Participant\NewParticipantRetrieved;
use App\League\Application\UseCase\Participant\CreateParticipant;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class NewParticipantRetrievedMessageHandler
{
    public function __construct(private readonly CreateParticipant $createParticipant)
    {
    }

    public function __invoke(NewParticipantRetrieved $newParticipant): void
    {
        $this->createParticipant->execute($newParticipant->id, $newParticipant->username, $newParticipant->isSuspended);
    }
}