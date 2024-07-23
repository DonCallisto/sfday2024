<?php

namespace App\League\Infrastructure\Repository\Doctrine;

use App\League\Domain\Model\League\League;
use App\League\Domain\Model\Participant\Participant;
use App\League\Domain\Model\Participant\ParticipantID;
use App\League\Domain\Repository\ParticipantRepositoryInterface;
use App\League\Domain\Service\Participant\Provider\Exception\ParticipantNotFoundException;
use Doctrine\ORM\EntityManagerInterface;

final class ParticipantRepository implements ParticipantRepositoryInterface
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    public function findByID(ParticipantID $id): Participant
    {
        $participant = $this->em
            ->createQueryBuilder()
            ->select('p')
            ->from(Participant::class, 'p')
            ->where('p.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();

        if ($participant === null) {
            throw ParticipantNotFoundException::withID($id);
        }

        return $participant;
    }

    public function findByUsername(string $username): Participant
    {
        $participant = $this->em
            ->createQueryBuilder()
            ->select('p')
            ->from(Participant::class, 'p')
            ->where('p.username = :username')
            ->setParameter('username', $username)
            ->getQuery()
            ->getOneOrNullResult();

        if ($participant === null) {
            throw ParticipantNotFoundException::withUsername($username);
        }

        return $participant;
    }

    public function save(Participant $participant): void
    {
        $this->em->persist($participant);
        $this->em->flush();
    }

    public function getNumberOfOwnedLeagues(ParticipantID $id): int
    {
        return (int) $this->em
            ->createQueryBuilder()
            ->select('COUNT(l.id)')
            ->from(League::class, 'l')
            ->where('l.ownerID = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getSingleScalarResult();
    }
}