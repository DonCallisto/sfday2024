<?php

namespace App\User\Application\Service;

use App\Shared\Application\Mailer\Exception\MailerSendingException;
use App\Shared\Application\Mailer\MailerInterface;
use App\Shared\Domain\Event\EventDispatcherInterface;
use App\User\Domain\Event\ScheduleRevokeUserSuspension;
use App\User\Domain\Model\User\UserID;
use App\User\Domain\Repository\UserRepositoryInterface;
use Symfony\Component\Uid\Uuid;

final class UserSuspendedHandler
{
    public function __construct(
        private readonly EventDispatcherInterface $eventDispatcher,
        private readonly MailerInterface $mailer,
        private readonly string $sender,
        private readonly UserRepositoryInterface $userRepository
    ) {

    }

    /**
     * @throws MailerSendingException
     */
    public function execute(UserSuspendedData $data): void
    {
        // @todo we can extract only needed data for this specific service (something like a view model)
        $suspendedUser = $this->userRepository->findById(new UserID(Uuid::fromString($data->userID)));

        $msg = <<<EOT
Hello {$suspendedUser->getUsername()}, 
your account has been suspended by {$data->suspendedByUsername} until {$data->suspendedTill->format('Y-m-d H:i:s')}.
The reason of your suspension is: {$data->reason}
EOT;

        $this->eventDispatcher->dispatch(new ScheduleRevokeUserSuspension($suspendedUser->getId(), $data->suspendedTill));
        $this->mailer->send($this->sender, $suspendedUser->getEmail(), 'Your account has been suspended!', $msg);
    }
}