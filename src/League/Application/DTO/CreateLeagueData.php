<?php

namespace App\League\Application\DTO;

final class CreateLeagueData
{
    public ?string $name = null;
    public ?int $maxNumberOfParticipants = null;
    public bool $isPrivate;
    public ?string $password = null;
}