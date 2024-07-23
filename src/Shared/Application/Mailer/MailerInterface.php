<?php

namespace App\Shared\Application\Mailer;

use App\Shared\Application\Mailer\Exception\MailerSendingException;

interface MailerInterface
{
    /**
     * !! This is a simplified interface. It's here to show the concept of the Adapter pattern. !!
     *
     * @throws MailerSendingException
     */
    public function send(string $from, string $to, string $subject, string $body);
}