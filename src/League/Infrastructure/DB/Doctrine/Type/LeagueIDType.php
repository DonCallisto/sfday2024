<?php

namespace App\League\Infrastructure\DB\Doctrine\Type;

use App\League\Domain\Model\League\LeagueID;
use App\Shared\Infrastructure\Doctrine\Type\BaseIDType;

final class LeagueIDType extends BaseIDType
{
    protected function getIDFQCN(): string
    {
        return LeagueID::class;
    }

    public function getName(): string
    {
        return 'league_id';
    }
}