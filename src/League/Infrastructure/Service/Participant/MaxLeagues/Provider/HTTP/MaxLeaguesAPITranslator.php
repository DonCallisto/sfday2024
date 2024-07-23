<?php

namespace App\League\Infrastructure\Service\Participant\MaxLeagues\Provider\HTTP;

use App\League\Domain\UseCase\Utils\LeagueChecker\MaxLeagues;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

final class MaxLeaguesAPITranslator
{
    /**
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     */
    public function translate(ResponseInterface $response): MaxLeagues
    {
        $data = $response->toArray();

        return new MaxLeagues($data['maxLeaguesAsCreator'], $data['maxLeaguesAsParticipant']);
    }
}