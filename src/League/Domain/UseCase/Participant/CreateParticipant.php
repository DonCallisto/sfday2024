<?php

namespace App\League\Domain\UseCase\Participant;

use App\League\Domain\Model\Participant\Participant;
use App\League\Domain\Model\Participant\ParticipantID;
use App\League\Domain\Repository\ParticipantRepositoryInterface;
use App\Shared\Domain\Event\EventDispatcherInterface;
use App\Shared\Domain\Event\HasEventsToDispatchTrait;

final class CreateParticipant
{
    use HasEventsToDispatchTrait;

    public function __construct(
        private readonly ParticipantRepositoryInterface $participantRepository,
        private readonly EventDispatcherInterface $eventDispatcher
    ) {
    }

    public function execute(ParticipantID $participantID, string $username, bool $enabled): void
    {
        $participant = new Participant($participantID, $username);
        if (!$enabled) {
            $participant->disable();
        }

        $this->participantRepository->save($participant);

        $this->dispatchEvents($participant, $this->eventDispatcher);
    }
}