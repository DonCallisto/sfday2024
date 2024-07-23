<?php

namespace App\League\Application\Service\Participant\Provider;

use App\League\Domain\Model\Participant\Participant;
use App\League\Domain\Service\Participant\Provider\Exception\ParticipantNotFoundException;
use App\League\Domain\Service\Participant\Provider\ParticipantProviderInterface;
use App\Shared\Domain\Event\EventInterface;

final class ParticipantChainedProvider implements ParticipantProviderInterface
{
    /**
     * @var list<ParticipantProviderApplicationInterface>
     */
    private readonly array $providers;

    /**
     * @var list<EventInterface>
     */
    private array $events = [];

    public function __construct(iterable $providers)
    {
        $providers = iterator_to_array($providers);

        usort(
            $providers,
            fn (ParticipantProviderApplicationInterface $a, ParticipantProviderApplicationInterface $b) => $a->priority() <=> $b->priority()
        );
        $this->providers = $providers;
    }

    public function provide(string $username): Participant
    {
        $this->events = [];

        foreach ($this->providers as $provider) {
            try {
                $participant = $provider->provide($username);
                $this->events = $provider->recordedEvents();

                return $participant;
            } catch (ParticipantNotFoundException) {
                continue;
            }
        }

        throw ParticipantNotFoundException::withUsername($username);
    }

    public function recordedEvents(): array
    {
        return $this->events;
    }
}