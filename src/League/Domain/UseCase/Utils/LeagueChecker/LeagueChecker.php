<?php

namespace App\League\Domain\UseCase\Utils\LeagueChecker;

use App\League\Domain\Model\Participant\Participant;
use App\League\Domain\Repository\ParticipantRepositoryInterface;

final class LeagueChecker
{

    // !! We used an API for this check. Reasons listed in `League.php` !!
    public function __construct(
        private readonly MaxLeaguesProviderInterface $maxLeaguesProvider,
        private readonly ParticipantRepositoryInterface $participantRepository
    ) {
    }

    public function canCreate(Participant $participant): bool
    {
        try {
            $maxLeagues = $this->maxLeaguesProvider->provide($participant->getId());
        } catch (\Throwable) {
            return false;
        }

        $ownedLeagues = $this->participantRepository->getNumberOfOwnedLeagues($participant->getId());

        return $ownedLeagues < $maxLeagues->asOwner;
    }
}