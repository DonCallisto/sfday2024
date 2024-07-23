<?php

namespace App\User\Infrastructure\Model\User\Symfony;

use App\User\Domain\Model\User\User;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

final readonly class SymfonyUser implements UserInterface, PasswordAuthenticatedUserInterface
{

    /**
     * @param list<string> $roles
     */
    public function __construct(private string $id, private string $identifier, private string $hashedPassword, private array $roles)
    {
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getUserIdentifier(): string
    {
        return $this->identifier;
    }

    public function getPassword(): string
    {
        return $this->hashedPassword;
    }

    /**
     * @return list<string>
     */
    public function getRoles(): array
    {
        if (empty($this->roles)) {
            return ['ROLE_USER'];
        }

        return $this->roles;
    }

    public function eraseCredentials(): void
    {
        // NO-OP
    }
}