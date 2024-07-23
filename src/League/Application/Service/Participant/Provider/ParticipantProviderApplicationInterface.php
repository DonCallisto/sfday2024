<?php

namespace App\League\Application\Service\Participant\Provider;

use App\League\Domain\Service\Participant\Provider\ParticipantProviderInterface;

interface ParticipantProviderApplicationInterface extends ParticipantProviderInterface
{
    /**
     * The lower the priority, the sooner the provider will be used
     */
    public function priority(): int;
}