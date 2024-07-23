<?php

namespace App\Shared\Domain\Event;

use App\Shared\Domain\Event\Exception\DispatchException;

interface EventDispatcherInterface
{
    /**
     * @throws DispatchException
     */
    public function dispatch(EventInterface $message);
}