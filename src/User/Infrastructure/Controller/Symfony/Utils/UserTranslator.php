<?php

namespace App\User\Infrastructure\Controller\Symfony\Utils;

use App\User\Application\Repository\Exception\UserNotFoundException;
use App\User\Application\Repository\UserByIdentifierRepositoryInterface;
use App\User\Domain\Model\User\User;
use App\User\Domain\Model\User\UserID;
use Symfony\Component\Security\Core\User\UserInterface;

final class UserTranslator
{
    public function __construct(private readonly UserByIdentifierRepositoryInterface $userRepository)
    {
    }

    /**
     * @throws UserNotFoundException
     */
    public function toUser(UserInterface $user): User
    {
        $userIdentifier = $user->getUserIdentifier();
        $user = $this->userRepository->findByIdentifier($userIdentifier);
        if (!$user) {
            throw UserNotFoundException::withIdentifier($userIdentifier);
        }

        return $user;
    }

    public function toUserID(UserInterface $userID): UserID
    {
        $userIdentifier = $userID->getUserIdentifier();
        $userID = $this->userRepository->findUserIDByIdentifier($userIdentifier);
        if (!$userID) {
            throw UserNotFoundException::withIdentifier($userIdentifier);
        }

        return $userID;
    }
}