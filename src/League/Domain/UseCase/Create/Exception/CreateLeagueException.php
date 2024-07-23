<?php

namespace App\League\Domain\UseCase\Create\Exception;

use App\League\Domain\Model\League\Exception\LeagueException;
use App\League\Domain\Model\Participant\Participant;

final class CreateLeagueException extends LeagueException
{
    public static function sameName(string $name): self
    {
        return new self(sprintf('A league with name %s already exists', $name));
    }

    public static function participantIsDisabled(Participant $participant): self
    {
        return new self(sprintf('A league cannot be created as participant %s is disabled', $participant->getUsername()));
    }

    public static function maxLeaguesReached(Participant $participant): self
    {
        return new self(sprintf('A league cannot be created as participant %s has reached the maximum number of leagues as owner', $participant->getUsername()));
    }
}