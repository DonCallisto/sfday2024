<?php

namespace App\League\Domain\UseCase\Utils\LeagueChecker;

use App\League\Domain\Model\Participant\ParticipantID;
use App\League\Domain\Service\Participant\Provider\Exception\ParticipantNotFoundException;

interface MaxLeaguesProviderInterface
{
    /**
     * @throws ParticipantNotFoundException
     */
    public function provide(ParticipantID $participantID): MaxLeagues;
}