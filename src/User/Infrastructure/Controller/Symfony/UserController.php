<?php

namespace App\User\Infrastructure\Controller\Symfony;

use App\User\Application\DTO\SuspendUserData;
use App\User\Application\UseCase\Promote\PromoteUser;
use App\User\Application\UseCase\Suspend\SuspendUser;
use App\User\Domain\Model\Exception\PromitionException;
use App\User\Domain\Model\Exception\SuspensionException;
use App\User\Domain\Model\User\User;
use App\User\Infrastructure\Controller\Symfony\Utils\UserTranslator;
use App\User\Infrastructure\Form\Symfony\SuspendUserForm;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/user')]
class UserController extends AbstractController
{
    #[Route('/{id}/promote', name: 'app.user.promote')]
    #[IsGranted('ROLE_SUPER_ADMIN')]
    public function promote(string $id, UserTranslator $translator, PromoteUser $promoteUser): Response
    {
        try {
            $promoteUser->execute($translator->toUserID($this->getUser())->toString(), $id);
            $this->addFlash('success', 'User has been promoted.');
        } catch (PromitionException $e) {
            $this->addFlash('error', $e->getMessage());
        }

        return $this->redirectToRoute('app.user.admin_dashboard');
    }

    #[Route('/{id}/suspend', name: 'app.user.suspend')]
    #[IsGranted('ROLE_SUPER_ADMIN')]
    public function suspend(
        string $id,
        UserTranslator $translator,
        SuspendUser $suspendUser,
        Request $request
    ): Response {
        $dto = new SuspendUserData();
        $form = $this->createForm(SuspendUserForm::class, $dto);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $suspendUser->suspend($translator->toUserID($this->getUser())->toString(), $id, $dto);
                $this->addFlash('success', 'User has been suspended.');
            } catch (SuspensionException $e) {
                $this->addFlash('error', $e->getMessage());
            }

            return $this->redirectToRoute('app.user.admin_dashboard');
        }

        return $this->render('user/suspend.html.twig', [
            'form' => $form->createView()
        ]);
    }

    // @toodo posso evitare di caricarmi tutto l'utente, se sì, eliminare il resolver symfony
    #[Route('/{id}/suspended', name: 'app.user.suspended')]
    public function suspended(User $suspendedUser): Response
    {
        return $this->render('user/suspended.html.twig', [
            'user' => $suspendedUser
        ]);
    }

    #[Route('/{id}/revoke-suspension', name: 'app.user.revoke_suspension')]
    #[IsGranted('ROLE_SUPER_ADMIN')]
    public function revokeSuspension(
        string $id,
        UserTranslator $translator,
        SuspendUser $suspendUser
    ): Response {
        try {
            $suspendUser->revokeSuspension($translator->toUserID($this->getUser())->toString(), $id);
            $this->addFlash('success', 'User suspension has been revoked.');
        } catch (SuspensionException $e) {
            $this->addFlash('error', $e->getMessage());
        }

        return $this->redirectToRoute('app.user.admin_dashboard');
    }
}