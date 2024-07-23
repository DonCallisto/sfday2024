<?php

namespace App\User\Infrastructure\Controller\Symfony;

use App\User\Application\API\UserDetail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/users')]
class ApiController extends AbstractController
{
    #[Route('/{username}', name: 'app.user.api.user_detail')]
    public function index(string $username, UserDetail $userDetail): Response
    {
        $user = $userDetail->get($username);
        if (!$user) {
            return new Response(null, Response::HTTP_NO_CONTENT);
        }

        // !! It's not a great idea to expose the whole model as it is as we're, somehow, bounding the consumer of this API. Done for simplicity !!
        return $this->json($user);
    }
}