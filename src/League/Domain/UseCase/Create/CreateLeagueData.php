<?php

namespace App\League\Domain\UseCase\Create;

final readonly class CreateLeagueData
{
    private function __construct(
        public string $name,
        public int $maxNumberOfParticipants,
        public bool $isPrivate,
        public ?string $hashedPassword
    ) {
        if ($this->isPrivate && !$this->hashedPassword) {
            throw new \LogicException('A private league must have a password');
        }
    }

    public static function public(string $name, int $maxNumberOfParticipants): self
    {
        return new self($name, $maxNumberOfParticipants, false, null);
    }

    public static function private(string $name, int $maxNumberOfParticipants, string $hashedPassword): self
    {
        return new self($name, $maxNumberOfParticipants, true, $hashedPassword);
    }
}