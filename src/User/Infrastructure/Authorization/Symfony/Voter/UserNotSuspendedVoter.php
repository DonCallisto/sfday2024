<?php

namespace App\User\Infrastructure\Authorization\Symfony\Voter;

use App\User\Application\Repository\Exception\UserNotFoundException;
use App\User\Infrastructure\Controller\Symfony\Utils\UserTranslator;
use App\User\Infrastructure\Model\User\Symfony\SymfonyUser;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

final class UserNotSuspendedVoter extends Voter
{
    public const USER_NOT_SUSPENDED = 'user_not_suspended';

    public function __construct(private readonly UserTranslator $userTranslator)
    {
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        return $attribute == self::USER_NOT_SUSPENDED;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof SymfonyUser) {
            return false;
        }

        try {
            $user = $this->userTranslator->toUser($user);
        } catch (UserNotFoundException) {
            return false;
        }

        return !$user->isSuspended();
    }
}