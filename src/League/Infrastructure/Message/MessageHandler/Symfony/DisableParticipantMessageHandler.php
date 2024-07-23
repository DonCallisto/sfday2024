<?php

namespace App\League\Infrastructure\Message\MessageHandler\Symfony;

use App\League\Application\UseCase\Participant\DisableParticipant as DisableParticipantUseCase;
use App\League\Domain\Event\Participant\DisableParticipant;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class DisableParticipantMessageHandler
{
    public function __construct(private readonly DisableParticipantUseCase $disableParticipant)
    {
    }

    public function __invoke(DisableParticipant $disableParticipant): void
    {
        $this->disableParticipant->execute($disableParticipant->participantID);
    }
}