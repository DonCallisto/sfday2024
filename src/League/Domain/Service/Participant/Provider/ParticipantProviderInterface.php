<?php

namespace App\League\Domain\Service\Participant\Provider;

use App\League\Domain\Model\Participant\Participant;
use App\League\Domain\Service\Participant\Provider\Exception\ParticipantNotFoundException;
use App\Shared\Domain\Event\HasRecordedEventsInterface;

interface ParticipantProviderInterface extends HasRecordedEventsInterface
{
    /**
     * @throws ParticipantNotFoundException
     */
    public function provide(string $username): Participant;
}