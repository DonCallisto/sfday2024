<?php

namespace App\Accounting\Infrastructure\Controller\Symfony;

use App\Accounting\Application\Api\CustomerLeagues;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/customers')]
class ApiController extends AbstractController
{
    #[Route('/{id}/leagues ', name: 'app.accounting.api.customer.leagues')]
    public function index(string $id, CustomerLeagues $customerLeagues): Response
    {
        $customerLeaguesModel = $customerLeagues->get($id);
        if (!$customerLeaguesModel) {
            return new Response(null, Response::HTTP_NO_CONTENT);
        }

        return $this->json($customerLeaguesModel);
    }
}