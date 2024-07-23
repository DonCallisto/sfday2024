<?php

namespace App\User\Infrastructure\Http\Request\Listener\Symfony;

use App\Shared\Domain\Utils\DateTime\DateTimeProviderInterface;
use App\User\Application\Repository\UserByIdentifierRepositoryInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

#[AsEventListener]
final class SuspendedUserListener
{
    public function __construct(
        private readonly TokenStorageInterface $tokenStorage,
        private readonly UserByIdentifierRepositoryInterface $userRepository,
        private readonly DateTimeProviderInterface $dateTimeProvider,
        private readonly RouterInterface $router
    ) {
    }

    // @todo sottolineare come questo debba stare qui per evitare di creare una dipendenza poco visibile
    public function __invoke(RequestEvent $event): void
    {
        $route = $event->getRequest()->attributes->get('_route');
        if (!str_contains('user', $route)) {
            return;
        }

        if ($event->getRequest()->attributes->get('_route') === 'app.user.suspended') {
            return;
        }

        if (!$user = $this->tokenStorage->getToken()?->getUser()) {
            return;
        }

        $user = $this->userRepository->findByIdentifier($user->getUserIdentifier());
        if (!$user || !$user->isSuspended()) {
            return;
        }

        if ($this->dateTimeProvider->now() > $user->getSuspendedTill()) {
            return;
        }

        $event->setResponse(new RedirectResponse($this->router->generate('app.user.suspended', ['id' => $user->getId()])));
    }
}