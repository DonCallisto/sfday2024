<?php

namespace App\User\Infrastructure\DB\Doctrine\Type;

use App\Shared\Infrastructure\Doctrine\Type\BaseIDType;
use App\User\Infrastructure\Model\User\Symfony\SymfonyUserRoleID;

final class SymfonyUserRoleIDType extends BaseIDType
{
    protected function getIDFQCN(): string
    {
        return SymfonyUserRoleID::class;
    }

    public function getName(): string
    {
        return 'sf_user_role_id';
    }
}