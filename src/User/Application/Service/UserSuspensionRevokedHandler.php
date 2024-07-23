<?php

namespace App\User\Application\Service;

use App\Shared\Application\Mailer\Exception\MailerSendingException;
use App\Shared\Application\Mailer\MailerInterface;
use App\User\Domain\Model\User\UserID;
use App\User\Domain\Repository\UserRepositoryInterface;
use Symfony\Component\Uid\Uuid;

final class UserSuspensionRevokedHandler
{
    public function __construct(
        private readonly MailerInterface $mailer,
        private readonly string $sender,
        private readonly UserRepositoryInterface $userRepository
    ) {

    }

    /**
     * @throws MailerSendingException
     */
    public function execute(string $userID): void
    {
        // @todo we can extract only needed data for this specific service (something like a view model)
        $suspendedUser = $this->userRepository->findById(new UserID(Uuid::fromString($userID)));

        $msg = <<<EOT
Hello {$suspendedUser->getUsername()}, 
your suspension is now revoked.
EOT;

        $this->mailer->send($this->sender, $suspendedUser->getEmail(), 'Your suspension is now revoked!', $msg);
    }
}