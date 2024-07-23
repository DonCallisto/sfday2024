<?php

namespace App\League\Domain\Service\Participant\Provider\Exception;

use App\League\Domain\Model\Participant\ParticipantID;

class ParticipantNotFoundException extends \Exception
{
    public static function withID(ParticipantID $id): self
    {
        return new self(sprintf('Participant with ID %s not found', $id->toString()));
    }

    public static function withUsername(string $username): self
    {
        return new self(sprintf('Participant with username %s not found', $username));
    }
}