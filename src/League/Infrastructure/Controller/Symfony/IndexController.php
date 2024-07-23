<?php

declare(strict_types=1);

namespace App\League\Infrastructure\Controller\Symfony;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/league')]
class IndexController extends AbstractController
{
    #[Route('/', name: 'app.league.index')]
    public function index(): Response
    {
        return $this->render('league/index.html.twig');
    }
}
