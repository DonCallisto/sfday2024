<?php

namespace App\League\Infrastructure\Service\Participant\MaxLeagues\Provider\HTTP\Symfony;

use App\League\Domain\Model\Participant\ParticipantID;
use App\League\Domain\Service\Participant\Provider\Exception\ParticipantNotFoundException;
use App\League\Domain\UseCase\Utils\LeagueChecker\MaxLeagues;
use App\League\Domain\UseCase\Utils\LeagueChecker\MaxLeaguesProviderInterface;
use App\League\Infrastructure\Service\Participant\MaxLeagues\Provider\HTTP\MaxLeaguesAPITranslator;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class MaxLeagueAPIProvider implements MaxLeaguesProviderInterface
{
    public function __construct(
        private readonly HttpClientInterface $apiClient,
        private readonly RouterInterface $router,
        private readonly MaxLeaguesAPITranslator $translator
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
    public function provide(ParticipantID $participantID): MaxLeagues
    {
        $response = $this->apiClient
            ->request('GET', $this->router->generate('app.accounting.api.customer.leagues', ['id' => $participantID->toString()]));

        if ($response->getStatusCode() === 204) {
            throw ParticipantNotFoundException::withID($participantID);
        }

        return $this->translator->translate($response);
    }
}