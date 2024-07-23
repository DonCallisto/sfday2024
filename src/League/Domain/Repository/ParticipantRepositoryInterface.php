<?php

namespace App\League\Domain\Repository;

use App\League\Domain\Model\Participant\Participant;
use App\League\Domain\Model\Participant\ParticipantID;
use App\League\Domain\Service\Participant\Provider\Exception\ParticipantNotFoundException;

interface ParticipantRepositoryInterface
{
    /**
     * @throws ParticipantNotFoundException
     */
    public function findByUsername(string $username): Participant;

    /**
     * @throws ParticipantNotFoundException
     */
    public function findByID(ParticipantID $id): Participant;

    public function save(Participant $participant): void;

    public function getNumberOfOwnedLeagues(ParticipantID $id): int;
}