<?php

namespace App\User\Application\Repository;

use App\User\Domain\Model\User\User;
use App\User\Domain\Model\User\UserID;
use App\User\Domain\Repository\Exception\UserNotFoundException;

/*
 * !! Example of interface segregation principle. This interface, in particular, is used only at application/infrastucture level
 * so i didn't wanted to mix it with the domain level interfaces. It's just a preference of mine.
 */
interface UserByIdentifierRepositoryInterface
{
    /**
     * @throws UserNotFoundException
     */
    public function findByIdentifier(string $identifier): ?User;

    /**
     * @throws UserNotFoundException
     */
    public function findUserIDByIdentifier(string $identifier): ?UserID;
}