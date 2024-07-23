<?php

namespace App\Shared\Domain\Model;

use Symfony\Component\Uid\Uuid;

abstract readonly class BaseID
{
    /*
     * !! Even if it seems that third-party code is leaking into domain, this is perfectly fine. Do otherwise would force
     * us to provide an abstraction for Uuid which is not convenient. As we're passing a Uuid to the constructor, we're
     * safe for isolation things (like unit testing) !!
     */
    public function __construct(private Uuid $id)
    {
    }

    public function __toString(): string
    {
        return $this->toString();
    }

    public function toString(): string
    {
        return $this->id->toString();
    }
}