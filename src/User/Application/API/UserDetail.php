<?php

namespace App\User\Application\API;

use App\User\Application\API\Model\UserDetailModel;
use App\User\Application\Repository\UserByIdentifierRepositoryInterface;
use App\User\Domain\Repository\Exception\UserNotFoundException;

final class UserDetail
{
    public function __construct(private readonly UserByIdentifierRepositoryInterface $userRepository)
    {
    }

    public function get(string $username): ?UserDetailModel
    {
        try {
            $user = $this->userRepository->findByIdentifier($username);
        } catch (UserNotFoundException) {
            return null;
        }

        return new UserDetailModel(
            $user->getId()->toString(),
            $user->getUsername(),
            $user->isSuspended()
        );
    }
}