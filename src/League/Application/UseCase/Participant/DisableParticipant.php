<?php

namespace App\League\Application\UseCase\Participant;

use App\League\Domain\Model\Participant\ParticipantID;
use App\League\Domain\Repository\ParticipantRepositoryInterface;
use App\League\Domain\Service\Participant\Provider\Exception\ParticipantNotFoundException;
use App\League\Domain\UseCase\Participant\DisableParticipant as DisableParticipantDomainUseCase;
use App\Shared\Domain\Event\EventDispatcherInterface;
use App\Shared\Domain\Event\HasEventsToDispatchTrait;
use Symfony\Component\Uid\Uuid;

final class DisableParticipant
{
    use HasEventsToDispatchTrait;

    public function __construct(
        private readonly ParticipantRepositoryInterface $participantRepository,
        private readonly DisableParticipantDomainUseCase $disableParticipantDomainUseCase,
        private readonly EventDispatcherInterface $eventDispatcher
    ) {
    }

    public function execute(string $participantID): void
    {
        try {
            $participant = $this->participantRepository->findByID(new ParticipantID(Uuid::fromString($participantID)));
        } catch (ParticipantNotFoundException) {
            return;
        }

        $this->disableParticipantDomainUseCase->execute($participant);
    }
}