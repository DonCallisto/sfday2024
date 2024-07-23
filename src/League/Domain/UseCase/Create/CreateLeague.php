<?php

namespace App\League\Domain\UseCase\Create;

use App\League\Domain\Model\League\Exception\EmptyPasswordException;
use App\League\Domain\Model\League\League;
use App\League\Domain\Model\Participant\Participant;
use App\League\Domain\Repository\LeagueRepositoryInterface;
use App\League\Domain\Service\Participant\Provider\Exception\ParticipantNotFoundException;
use App\League\Domain\Service\Participant\Provider\ParticipantProviderInterface;
use App\League\Domain\UseCase\Create\Exception\CreateLeagueException;
use App\League\Domain\UseCase\Utils\LeagueChecker\LeagueChecker;
use App\Shared\Domain\Event\EventDispatcherInterface;
use App\Shared\Domain\Event\HasEventsToDispatchTrait;

final class CreateLeague
{
    use HasEventsToDispatchTrait;

    public function __construct(
        private readonly ParticipantProviderInterface $participantProvider,
        private readonly LeagueChecker $leagueChecker,
        private readonly EventDispatcherInterface $eventDispatcher,
        private readonly LeagueRepositoryInterface $leagueRepository
    ) {
    }

    /**
     * @throws CreateLeagueException
     * @throws ParticipantNotFoundException
     */
    public function execute(string $ownerUsername, CreateLeagueData $leagueData): void
    {
        if ($this->leagueRepository->leagueExists($leagueData->name)) {
            throw CreateLeagueException::sameName($leagueData->name);
        }

        $participant = $this->participantProvider->provide($ownerUsername);

        if (!$participant->isEnabled()) {
            throw CreateLeagueException::participantIsDisabled($participant);
        }

        if (!$this->leagueChecker->canCreate($participant)) {
            throw CreateLeagueException::maxLeaguesReached($participant);
        }

        $league = $this->createLeague($participant, $leagueData);

        $this->leagueRepository->save($league);

        $this->dispatchEvents($this->participantProvider, $this->eventDispatcher);
    }

    /**
     * @throws CreateLeagueException
     */
    private function createLeague(Participant $participant, CreateLeagueData $leagueData): League
    {
        $leagueID = $this->leagueRepository->nextId();

        if ($leagueData->isPrivate) {
            try {
                return League::private(
                    $leagueID,
                    $leagueData->name,
                    $leagueData->maxNumberOfParticipants,
                    $leagueData->hashedPassword,
                    $participant->getId()
                );
            } catch (EmptyPasswordException $exception) {
                throw new CreateLeagueException($exception->getMessage(), $exception->getCode(), $exception);
            }
        }

        return League::public(
            $leagueID,
            $leagueData->name,
            $leagueData->maxNumberOfParticipants,
            $participant->getId()
        );
    }
}