<?php

namespace App\Accounting\Domain\Model\Customer;

use App\Accounting\Domain\Model\Subscription\SubscriptionPlan;
use App\Shared\Domain\Event\EventInterface;
use App\Shared\Domain\Event\HasRecordedEventsInterface;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
final class Customer implements HasRecordedEventsInterface
{
    #[ORM\Id]
    #[ORM\Column(type: 'customer_id')]
    private readonly CustomerID $id;

    #[ORM\Column(type: 'string', length: 50, unique: true)]
    private string $name;

    #[ORM\Column(type: 'string', length: 50, unique: true)]
    private string $surname;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    private string $email;

    #[ORM\Column(type: 'string', enumType: SubscriptionPlan::class)]
    private SubscriptionPlan $subscriptionPlan = SubscriptionPlan::FREE;

    /**
     * @var list<EventInterface>
     */
    private array $events = [];

    public function __construct(CustomerID $id, string $name, string $surname, string $email)
    {
        $this->id = $id;
        $this->name = $name;
        $this->surname = $surname;
        $this->email = $email;
    }

    public function recordedEvents(): array
    {
        return $this->events;
    }

    public function hasFreePlan(): bool
    {
        return $this->subscriptionPlan == SubscriptionPlan::FREE;
    }

    public function hasBasicPlan(): bool
    {
        return $this->subscriptionPlan == SubscriptionPlan::BASIC;
    }

    public function hasPremiumPlan(): bool
    {
        return $this->subscriptionPlan == SubscriptionPlan::PREMIUM;
    }

    public function hasUnlimitedPlan(): bool
    {
        return $this->subscriptionPlan == SubscriptionPlan::UNLIMITED;
    }
}