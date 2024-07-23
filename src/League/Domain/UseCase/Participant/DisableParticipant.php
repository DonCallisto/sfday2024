<?php

namespace App\League\Domain\UseCase\Participant;

use App\League\Domain\Model\Participant\Participant;
use App\League\Domain\Repository\ParticipantRepositoryInterface;
use App\Shared\Domain\Event\EventDispatcherInterface;
use App\Shared\Domain\Event\HasEventsToDispatchTrait;

final class DisableParticipant
{
    use HasEventsToDispatchTrait;

    public function __construct(
        private readonly ParticipantRepositoryInterface $participantRepository,
        private readonly EventDispatcherInterface $dispatcher
    ) {
    }

    public function execute(Participant $participant): void
    {
        $participant->disable();
        $this->participantRepository->save($participant);

        $this->dispatchEvents($participant, $this->dispatcher);
    }
}