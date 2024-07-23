<?php

namespace App\User\Infrastructure\Controller\Symfony;

use App\User\Infrastructure\Authorization\Symfony\Voter\UserNotSuspendedVoter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/dashboard')]
#[IsGranted(UserNotSuspendedVoter::USER_NOT_SUSPENDED)]
class DashboardController extends AbstractController
{
    #[Route('/', name: 'app.user.dashboard')]
    public function index(): Response
    {
        return $this->render('user/dashboard/index.html.twig');
    }
}