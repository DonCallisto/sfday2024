<?php

namespace App\League\Infrastructure\Repository\Doctrine;

use App\League\Domain\Model\League\League;
use App\League\Domain\Model\League\LeagueID;
use App\League\Domain\Repository\LeagueRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Uid\Uuid;

final class LeagueRepository implements LeagueRepositoryInterface
{
    public function __construct(private readonly EntityManagerInterface $em)
    {
    }

    public function nextId(): LeagueID
    {
        return new LeagueID(Uuid::v4());
    }

    public function save(League $league): void
    {
        $this->em->persist($league);
        $this->em->flush();
    }

    public function leagueExists(string $name): bool
    {
        return (bool) $this->em->createQueryBuilder()
            ->select('l')
            ->from(League::class, 'l')
            ->where('l.name = :name')
            ->setParameter('name', $name)
            ->getQuery()
            ->getOneOrNullResult();
    }
}