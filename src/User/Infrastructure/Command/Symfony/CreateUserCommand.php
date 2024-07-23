<?php

declare(strict_types=1);

namespace App\User\Infrastructure\Command\Symfony;

use App\User\Application\Event\UserCreatedViaCLI;
use App\User\Domain\Repository\UserRepositoryInterface;
use App\User\Domain\UseCase\Create\CreateUser;
use App\User\Domain\UseCase\Create\CreateUserData;
use App\User\Infrastructure\Model\User\Symfony\SymfonyUser;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(name: 'app:user:create', description: 'Create a user programmatically')]
final class CreateUserCommand extends Command
{
    private const NAME_OPTION = 'name';
    private const SURNAME_OPTION = 'surname';
    private const USERNAME_OPTION = 'username';
    private const EMAIL_OPTION = 'email';
    private const PWD_OPTION = 'password';
    private const SUPER_ADMIN_OPTION = 'superAdmin';

    public function __construct(
        private readonly CreateUser $createUser,
        private readonly UserRepositoryInterface $userRepository,
        private readonly MessageBusInterface $bus,
        private readonly UserPasswordHasherInterface $pwdHasher
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setDefinition(
            new InputDefinition([
                new InputOption(self::NAME_OPTION, 'na', InputOption::VALUE_REQUIRED, 'Name'),
                new InputOption(self::SURNAME_OPTION, 'su', InputOption::VALUE_REQUIRED, 'Surname'),
                new InputOption(self::USERNAME_OPTION, 'u', InputOption::VALUE_REQUIRED, 'Username'),
                new InputOption(self::EMAIL_OPTION, 'em', InputOption::VALUE_REQUIRED, 'Email'),
                new InputOption(self::PWD_OPTION, 'p', InputOption::VALUE_REQUIRED, 'Password'),
                new InputOption(self::SUPER_ADMIN_OPTION, 's', InputOption::VALUE_NONE, 'If user will be super admin'),
            ])
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $username = $input->getOption(self::USERNAME_OPTION);
        $email = $input->getOption(self::EMAIL_OPTION);
        $superAdmin = $input->getOption('superAdmin');
        $password = $input->getOption(self::PWD_OPTION);


        $userId = $this->userRepository->nextId();

        try {
            $this->createUser->execute(
                new CreateUserData(
                    $userId,
                    $input->getOption(self::NAME_OPTION),
                    $input->getOption(self::SURNAME_OPTION),
                    $username,
                    $email,
                    // we need to create programmatically the SymfonyUser to satisfy the Hasher
                    $this->pwdHasher->hashPassword(new SymfonyUser($userId->toString(), $username, $password, []), $password),
                    (bool) $superAdmin
                )
            );
        } catch (\Throwable $e) {
            $output->writeln('<error>' . $e->getMessage() . '</error>');

            return Command::FAILURE;
        }

        try {
            $this->bus->dispatch(new UserCreatedViaCLI($userId->toString(), $email, (bool) $superAdmin));
        } catch (ExceptionInterface) {
            // Save event for a retry or whatever
        }

        return Command::SUCCESS;
    }
}
