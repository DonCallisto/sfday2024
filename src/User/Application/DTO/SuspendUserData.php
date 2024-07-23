<?php

namespace App\User\Application\DTO;

final class SuspendUserData
{
    public ?\DateTimeInterface $suspendedTill = null;
    public string $reason = '';
}