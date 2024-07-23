<?php

namespace App\User\Infrastructure\Controller\Symfony;

use App\User\Domain\Repository\UserRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin_dashboard')]
class AdminDashboardController extends AbstractController
{
    #[Route('/', name: 'app.user.admin_dashboard')]
    #[IsGranted('ROLE_SUPER_ADMIN')]
    public function index(UserRepositoryInterface $userRepository): Response
    {
        // @todo we could have used a view model here. It's not necessary for this simple case.
        return $this->render('user/admin_dashboard/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }
}