<?php

namespace App\Shared\Infrastructure\Utils\DateTime\System;

use App\Shared\Domain\Utils\DateTime\DateTimeProviderInterface;

final class DateTimeProvider implements DateTimeProviderInterface
{
    public function now(): \DateTimeImmutable
    {
        return new \DateTimeImmutable();
    }
}