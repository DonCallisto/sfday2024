<?php

namespace App\League\Infrastructure\Service\Participant\Provider\Repository\Doctrine;

use App\League\Application\Service\Participant\Provider\ParticipantProviderApplicationInterface;
use App\League\Domain\Model\Participant\Participant;
use App\League\Infrastructure\Repository\Doctrine\ParticipantRepository;

final class ParticipantRepositoryProvider implements ParticipantProviderApplicationInterface
{
    public function __construct(private readonly ParticipantRepository $participantRepository)
    {
    }

    public function provide(string $username): Participant
    {
        return $this->participantRepository->findByUsername($username);
    }

    public function recordedEvents(): array
    {
        return [];
    }

    public function priority(): int
    {
        return 1;
    }
}