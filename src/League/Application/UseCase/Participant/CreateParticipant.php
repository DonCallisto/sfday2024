<?php

namespace App\League\Application\UseCase\Participant;

use App\League\Domain\Model\Participant\ParticipantID;
use App\League\Domain\UseCase\Participant\CreateParticipant as CreateParticipantDomainUseCase;
use Symfony\Component\Uid\Uuid;

final class CreateParticipant
{
    public function __construct(private readonly CreateParticipantDomainUseCase $createParticipant)
    {

    }

    public function execute(string $participantID, string $username, bool $enabled): void
    {
        $this->createParticipant->execute(new ParticipantID(Uuid::fromString($participantID)), $username, $enabled);
    }
}