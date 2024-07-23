<?php

namespace App\Shared\Infrastructure\Doctrine\Type;

use App\Shared\Domain\Model\BaseID;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Symfony\Component\Uid\Uuid;

abstract class BaseIDType extends Type
{
    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        $column['length'] = 36;
        $column['fixed'] = true;

        return $platform->getStringTypeDeclarationSQL($column);
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): object
    {
        $className = $this->getIDFQCN();

        return new $className(Uuid::fromString($value));
    }

    protected abstract function getIDFQCN(): string;

    /**
     * @param BaseID $value
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): string
    {
        return $value->toString();
    }
}