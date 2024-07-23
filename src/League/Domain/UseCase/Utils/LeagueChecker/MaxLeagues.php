<?php

namespace App\League\Domain\UseCase\Utils\LeagueChecker;

final readonly class MaxLeagues
{
    public function __construct(public int $asOwner, public int $asParticipant)
    {
    }
}