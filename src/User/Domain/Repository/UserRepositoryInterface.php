<?php

namespace App\User\Domain\Repository;

use App\User\Domain\Model\User\User;
use App\User\Domain\Model\User\UserID;
use App\User\Domain\Repository\Exception\UserNotFoundException;

// !! Some methods should be moved to a read model repository but it's ok for now !!
interface UserRepositoryInterface
{
    public function nextId(): UserID;

    /**
     * @return list<User>
     */
    public function findAll(): array;

    /**
     * @throws UserNotFoundException
     */
    public function findById(UserID $id): User;


    public function save(User $user): void;

    public function userExists(string $username, string $email): bool;
}