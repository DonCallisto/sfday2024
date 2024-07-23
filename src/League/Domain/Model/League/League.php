<?php

namespace App\League\Domain\Model\League;

use App\League\Domain\Model\League\Exception\EmptyPasswordException;
use App\League\Domain\Model\League\Exception\LeagueException;
use App\League\Domain\Model\League\Exception\MinNumberOfParticipantsException;
use App\League\Domain\Model\Participant\ParticipantID;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
final class League
{
    #[ORM\Id]
    #[ORM\Column(type: 'league_id')]
    private readonly LeagueID $id;

    #[ORM\Column(type: 'string', length: 50, unique: true)]
    private string $name;

    #[ORM\Column(type: 'smallint')]
    private string $maxNumberOfParticipants;

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $password;

    #[ORM\Column(type: 'string', length: 36)]
    private ParticipantID $ownerID;

    /**
     * @throws LeagueException
     */
    private function __construct(LeagueID $id, string $name, int $maxNumberOfParticipants, ?string $password, ParticipantID $ownerID)
    {
        if ($maxNumberOfParticipants < 2) {
            throw MinNumberOfParticipantsException::tooLow(2);
        }

        if ($password !== null && trim($password) === '') {
            throw EmptyPasswordException::emptyPassword();
        }

        $this->id = $id;
        $this->name = $name;
        $this->maxNumberOfParticipants = $maxNumberOfParticipants;
        $this->password = $password;
        $this->ownerID = $ownerID;
    }

    /**
     * @throws LeagueException
     */
    public static function public(LeagueID $leagueID, string $name, int $maxNumberOfParticipants, ParticipantID $ownerID): self
    {
        return new self($leagueID, $name, $maxNumberOfParticipants, null, $ownerID);
    }

    /**
     * @throws LeagueException
     */
    public static function private(
        LeagueID $leagueID,
        string $name,
        int $maxNumberOfParticipants,
        string $password,
        ParticipantID $ownerID
    ): self {
        if (trim($password) === '') {
            throw EmptyPasswordException::emptyPassword();
        }

        return new self($leagueID, $name, $maxNumberOfParticipants, $password, $ownerID);
    }
}