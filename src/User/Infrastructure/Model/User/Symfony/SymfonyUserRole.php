<?php

namespace App\User\Infrastructure\Model\User\Symfony;

use App\User\Domain\Model\User\User;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
final class SymfonyUserRole
{
    #[ORM\Id]
    #[ORM\Column(type: 'sf_user_role_id')]
    private SymfonyUserRoleID $id;

    /**
     * @var list<string>
     */
    #[ORM\Column(type: 'json')]
    private array $roles;

    #[ORM\OneToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id')]
    private User $user;

    public function __construct(SymfonyUserRoleID $id, User $user, array $roles)
    {
        if (empty($roles)) {
            throw new \InvalidArgumentException('Roles cannot be empty');
        }

        $this->id = $id;
        $this->user = $user;
        $this->roles = $roles;
    }

    /**
     * @return list<string>
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    public function addRole(string $role): void
    {
        if (in_array($role, $this->roles)) {
            return;
        }

        $this->roles[] = $role;
    }
}