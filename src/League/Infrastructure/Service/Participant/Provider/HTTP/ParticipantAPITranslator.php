<?php

namespace App\League\Infrastructure\Service\Participant\Provider\HTTP;

use App\League\Domain\Model\Participant\Participant;
use App\League\Domain\Model\Participant\ParticipantID;
use Symfony\Component\Uid\Uuid;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

final class ParticipantAPITranslator
{
    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function translate(ResponseInterface $response): Participant
    {
        $data = $response->toArray();

        $participant = new Participant(new ParticipantID(Uuid::fromString($data['userID'])), $data['username']);

        if ($data['isSuspended']) {
            $participant->disable();
        }

        return $participant;
    }
}