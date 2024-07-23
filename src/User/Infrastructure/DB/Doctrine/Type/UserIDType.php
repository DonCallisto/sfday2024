<?php

namespace App\User\Infrastructure\DB\Doctrine\Type;

use App\Shared\Infrastructure\Doctrine\Type\BaseIDType;
use App\User\Domain\Model\User\UserID;

final class UserIDType extends BaseIDType
{
    protected function getIDFQCN(): string
    {
        return UserID::class;
    }

    public function getName(): string
    {
        return 'user_id';
    }
}