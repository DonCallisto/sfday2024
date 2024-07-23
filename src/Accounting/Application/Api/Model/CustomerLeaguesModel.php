<?php

namespace App\Accounting\Application\Api\Model;

final readonly class CustomerLeaguesModel
{
    // !! Pay attention to the ubiquitous language. League BC has the concept of owner, not creator !!
    public function __construct(public ?int $maxLeaguesAsCreator, public ?int $maxLeaguesAsParticipant)
    {
    }
}