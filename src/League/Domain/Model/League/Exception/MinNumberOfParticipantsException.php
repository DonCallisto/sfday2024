<?php

namespace App\League\Domain\Model\League\Exception;

class MinNumberOfParticipantsException extends LeagueException
{
    public static function tooLow(int $min): self
    {
        return new self(sprintf('A league must have at least %d participants', $min));
    }
}