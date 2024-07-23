<?php

namespace App\League\Domain\Model\Participant;

use App\Shared\Domain\Event\EventInterface;
use App\Shared\Domain\Event\HasRecordedEventsInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * !! We could have implemented this as a V.O. but, in order to do this, we would have to make HTTP calls to retrieve
 * all the data. For example, in order to check if this participant can create a league, we use an API call because we
 * would not let this be handled by eventual consistency as, if a league is created and some participant join it, we don't
 * have a compensation action. Moreover a league creation is something a participant do not much often so is pretty safe
 * to demand it to another BC.
 * For the status (enabled/disabled), on the contrary, we can take advantage of eventual consistency because this is
 * something transitory. Moreover we wanted to avoid an API call for every action a participant could perform inside this BC. !!
 */
#[ORM\Entity]
final class Participant implements HasRecordedEventsInterface
{
    #[ORM\Id]
    #[ORM\Column(type: 'participant_id')]
    private readonly ParticipantID $id;

    #[ORM\Column(type: 'string', length: 50, unique: true)]
    private string $username;


    // !! Again, here the obiquitous language is slightly different from the one in the user BC !!
    // @todo dovrei fare controllo accessi anche in base a questo.
    #[ORM\Column(type: 'boolean')]
    private bool $isEnabled = true;

    /**
     * @var list<EventInterface>
     */
    private array $events = [];

    public function __construct(ParticipantID $id, string $username)
    {
        $this->id = $id;
        $this->username = $username;
    }

    public function getId(): ParticipantID
    {
        return $this->id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function disable(): void
    {
        $this->isEnabled = false;
    }

    public function enable(): void
    {
        $this->isEnabled = true;
    }

    public function isEnabled(): bool
    {
        return $this->isEnabled;
    }

    public function recordedEvents(): array
    {
        return $this->events;
    }
}