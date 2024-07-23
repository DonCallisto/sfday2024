<?php

namespace App\Shared\Domain\Utils\DateTime;

interface DateTimeProviderInterface
{
    public function now(): \DateTimeImmutable;
}