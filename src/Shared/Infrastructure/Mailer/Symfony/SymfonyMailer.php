<?php

namespace App\Shared\Infrastructure\Mailer\Symfony;

use App\Shared\Application\Mailer\Exception\MailerSendingException;
use App\Shared\Application\Mailer\MailerInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface as SymfonyMailerInterface;
use Symfony\Component\Mime\Email;

final class SymfonyMailer implements MailerInterface
{
    public function __construct(private readonly SymfonyMailerInterface $mailer)
    {
    }

    public function send(string $from, string $to, string $subject, string $body): void
    {
        try {
            $this->mailer->send((new Email())
                ->from($from)
                ->to($to)
                ->subject($subject)
                ->text($body)
            );
        } catch (TransportExceptionInterface $e) { // @todo far vedere perché è importate wrappare l'eccezione
            // !! It's important to wrap the exception to avoid leaking third-party idiosyncrasies (see `send` method documentation) !!
            throw new MailerSendingException($e->getMessage());
        }
    }
}