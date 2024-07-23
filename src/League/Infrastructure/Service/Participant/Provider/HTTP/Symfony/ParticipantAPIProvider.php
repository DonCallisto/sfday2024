<?php

namespace App\League\Infrastructure\Service\Participant\Provider\HTTP\Symfony;

use App\League\Application\Events\Participant\NewParticipantRetrieved;
use App\League\Application\Service\Participant\Provider\ParticipantProviderApplicationInterface;
use App\League\Domain\Model\Participant\Participant;
use App\League\Domain\Service\Participant\Provider\Exception\ParticipantNotFoundException;
use App\League\Infrastructure\Service\Participant\Provider\HTTP\ParticipantAPITranslator;
use App\Shared\Domain\Event\EventInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

// !! This translation is part of an anti-corruption layer (ACL) !!
final class ParticipantAPIProvider implements ParticipantProviderApplicationInterface
{
    /**
     * @var list<EventInterface>
     */
    private array $events = [];

    public function __construct(
        private readonly HttpClientInterface $apiClient,
        private readonly RouterInterface $router,
        private readonly ParticipantAPITranslator $translator
    ) {
    }

    /**
     * @throws ParticipantNotFoundException
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function provide(string $username): Participant
    {
        $this->events = [];

        $response = $this->apiClient
            ->request('GET', $this->router->generate('app.user.api.user_detail', ['username' => $username]));

        if ($response->getStatusCode() === 204) {
            throw ParticipantNotFoundException::withUsername($username);
        }

        $participant = $this->translator->translate($response);

        $this->events[] = new NewParticipantRetrieved(
            $participant->getId(),
            $participant->getUsername(),
            $participant->isEnabled()
        );

        return $participant;
    }

    public function recordedEvents(): array
    {
        return $this->events;
    }

    public function priority(): int
    {
        return 2;
    }
}