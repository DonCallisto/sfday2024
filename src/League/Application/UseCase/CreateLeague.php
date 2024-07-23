<?php

namespace App\League\Application\UseCase;

use App\League\Application\DTO\CreateLeagueData;
use App\League\Domain\Model\League\Exception\LeagueException;
use App\League\Domain\Service\Participant\Provider\Exception\ParticipantNotFoundException;
use App\League\Domain\UseCase\Create\CreateLeague as CreateLeagueDomainUseCase;
use App\League\Domain\UseCase\Create\CreateLeagueData as CreateLeagueDomainData;

final class CreateLeague
{
    public function __construct(private readonly CreateLeagueDomainUseCase $createLeague)
    {
    }

    /**
     * @throws LeagueException
     * @throws ParticipantNotFoundException
     */
    public function execute(string $ownerUsername, CreateLeagueData $createLeagueData): void
    {
        if (!$createLeagueData->name) {
            throw new \LogicException('League name is required');
        }

        if (!$createLeagueData->maxNumberOfParticipants) {
            throw new \LogicException('Max number of participants is required');
        }

        if ($createLeagueData->isPrivate) {
            $this->createLeague->execute($ownerUsername, CreateLeagueDomainData::private(
                $createLeagueData->name,
                $createLeagueData->maxNumberOfParticipants,
                $createLeagueData->password // @todo password should be hashed. Didn't do for simplicity.
            ));

            return;
        }

        $this->createLeague->execute($ownerUsername, CreateLeagueDomainData::public(
            $createLeagueData->name,
            $createLeagueData->maxNumberOfParticipants,
        ));
    }
}