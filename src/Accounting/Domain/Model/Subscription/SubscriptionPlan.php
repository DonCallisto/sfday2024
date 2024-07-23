<?php

namespace App\Accounting\Domain\Model\Subscription;

enum SubscriptionPlan: string
{
    case FREE = 'free';
    case BASIC = 'basic';
    case PREMIUM = 'premium';
    case UNLIMITED = 'unlimited';
}