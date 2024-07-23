<?php

namespace App\User\Domain\Model\User;

use App\Shared\Domain\Event\EventInterface;
use App\Shared\Domain\Event\HasRecordedEventsInterface;
use App\User\Domain\Event\UserPromoted;
use App\User\Domain\Event\UserSuspendend;
use App\User\Domain\Event\UserSuspensionRevoked;
use App\User\Domain\Model\Exception\PromitionException;
use App\User\Domain\Model\Exception\SuspensionException;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
final class User implements HasRecordedEventsInterface
{
    #[ORM\Id]
    #[ORM\Column(type: 'user_id')]
    private readonly UserID $id;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    private string $email;

    #[ORM\Column(type: 'string', length: 50, unique: true)]
    private string $username;

    #[ORM\Column(type: 'string')]
    private string $password;

    #[ORM\Column(type: 'boolean')]
    private bool $superAdmin = false;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private ?\DateTimeImmutable $suspendedTill = null;

    /**
     * @var list<EventInterface>
     */
    private array $events = [];

    public function __construct(UserID $id, string $username, string $email, string $password)
    {
        $this->id = $id;
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
    }

    public function getId(): UserID
    {
        return $this->id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * Return HASHED (!) password
     *
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function isSuperAdmin(): bool
    {
        return $this->superAdmin;
    }

    public function isSuspended(): bool
    {
        return (bool) $this->getSuspendedTill();
    }

    // !! Some methods, like this one, should be moved to a read model. It's ok for now !!
    public function getSuspendedTill(): ?\DateTimeImmutable
    {
        return $this->suspendedTill;
    }

    /**
     * @throws PromitionException
     */
    public function promote(): void
    {
        if ($this->isSuspended()) {
            throw PromitionException::userIsSupended();
        }

        $this->superAdmin = true;

        $this->events[] = new UserPromoted($this->id->toString());
    }

    /**
     * @throws SuspensionException
     */
    public function suspend(self $suspendedBy, \DateTimeInterface $now, \DateTimeInterface $suspendedTill, string $reason): void
    {
        if ($this->superAdmin) {
            throw SuspensionException::userIsSuperAdmin();
        }

        if ($now > $suspendedTill) {
            throw SuspensionException::suspensionInPast();
        }

        if ($this->suspendedTill > $suspendedTill) {
            throw SuspensionException::userIsAlreadySuspended();
        }

        // !! Pastel's law !!
        $this->suspendedTill = \DateTimeImmutable::createFromInterface($suspendedTill);

        /*
         * !! Pay attention to event name. Even if I know what this operation implies today (a mail will be send)
         * I name it after the action that's been performed !!
         */
        $this->events[] = new UserSuspendend($this->id->toString(), $suspendedBy->username, $suspendedTill, $reason);
    }

    /**
     * @throws SuspensionException
     */
    public function revokeSuspension(): void
    {
        if (!$this->suspendedTill) {
            throw SuspensionException::revokedOnNotSuspendedUser();
        }

        $this->suspendedTill = null;

        $this->events[] = new UserSuspensionRevoked($this->id->toString());
    }

    public function recordedEvents(): array
    {
        return $this->events;
    }
}