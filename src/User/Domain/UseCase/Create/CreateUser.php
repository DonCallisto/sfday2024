<?php

namespace App\User\Domain\UseCase\Create;

use App\Shared\Domain\Event\EventDispatcherInterface;
use App\User\Domain\Event\UserCreated;
use App\User\Domain\Model\Exception\PromitionException;
use App\User\Domain\Model\User\User;
use App\User\Domain\Repository\UserRepositoryInterface;
use App\User\Domain\UseCase\Create\Exception\UserExistsException;
use App\User\Domain\UseCase\Promote\PromoteUser;

final class CreateUser
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly PromoteUser $promoteUser,
        private readonly EventDispatcherInterface $eventDispatcher
    ) {
    }

    /**
     * @throws UserExistsException
     * @throws \RuntimeException
     */
    public function execute(CreateUserData $userData): User
    {
        if ($this->userRepository->userExists($userData->username, $userData->email)) {
            throw UserExistsException::userExists($userData->username, $userData->email);
        }

        $user = new User($userData->userId, $userData->username, $userData->email, $userData->hashedPassword);
        try {
            $this->userRepository->save($user);
        } catch (\Exception $e) {
            throw new \RuntimeException('An error occurred while saving the user', 0, $e);
        }

        $this->eventDispatcher->dispatch(new UserCreated(
            $userData->userId,
            $userData->name,
            $userData->surname,
            $userData->username,
            $userData->email
        ));


        if ($userData->isSuperAdmin) {
            try {
                $this->promoteUser->execute($user);
            } catch (PromitionException $e) {
                throw new \LogicException('An error occurred while promoting the user', 0, $e);
            }
        }

        return $user;
    }
}