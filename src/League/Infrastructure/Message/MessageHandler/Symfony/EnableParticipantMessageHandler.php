<?php

namespace App\League\Infrastructure\Message\MessageHandler\Symfony;

use App\League\Domain\Event\Participant\EnableParticipant;
use App\League\Application\UseCase\Participant\EnableParticipant as EnableParticipantUseCase;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class EnableParticipantMessageHandler
{
    public function __construct(private readonly EnableParticipantUseCase $enableParticipant)
    {
    }

    public function __invoke(EnableParticipant $enableParticipant): void
    {
        $this->enableParticipant->execute($enableParticipant->participantID);
    }
}