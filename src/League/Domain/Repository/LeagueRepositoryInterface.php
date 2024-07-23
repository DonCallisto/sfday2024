<?php

namespace App\League\Domain\Repository;

use App\League\Domain\Model\League\League;
use App\League\Domain\Model\League\LeagueID;

interface LeagueRepositoryInterface
{
    public function nextId(): LeagueID;

    public function save(League $league): void;

    public function leagueExists(string $name): bool;
}