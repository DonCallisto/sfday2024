<?php

namespace App\League\Infrastructure\DB\Doctrine\Type;

use App\League\Domain\Model\Participant\ParticipantID;
use App\Shared\Infrastructure\Doctrine\Type\BaseIDType;

final class ParticipantIDType extends BaseIDType
{
    protected function getIDFQCN(): string
    {
        return ParticipantID::class;
    }

    public function getName(): string
    {
        return 'participant_id';
    }
}