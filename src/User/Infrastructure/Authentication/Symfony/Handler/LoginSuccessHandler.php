<?php

namespace App\User\Infrastructure\Authentication\Symfony\Handler;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;

final class LoginSuccessHandler implements AuthenticationSuccessHandlerInterface
{
    public function __construct(private readonly RouterInterface $router)
    {
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token): Response
    {
        $roles = $token->getRoleNames();
        if (in_array('ROLE_SUPER_ADMIN', $roles)) {
            return new RedirectResponse($this->router->generate('app.user.admin_dashboard'));
        }

        return new RedirectResponse($this->router->generate('app.user.dashboard'));
    }
}