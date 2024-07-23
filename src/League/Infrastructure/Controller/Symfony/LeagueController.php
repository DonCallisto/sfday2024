<?php

namespace App\League\Infrastructure\Controller\Symfony;

use App\League\Application\DTO\CreateLeagueData;
use App\League\Application\UseCase\CreateLeague;
use App\League\Domain\Model\League\Exception\LeagueException;
use App\League\Domain\Service\Participant\Provider\Exception\ParticipantNotFoundException;
use App\League\Infrastructure\Form\Symfony\League\CreateLeagueForm;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/league')]
class LeagueController extends AbstractController
{
    // @todo impostare il controllo di accesso per evitare che un participant sospeso possa fare queste cose (da riportare anche nel BC user?)
    #[Route('/new', name: 'app.league.new')]
    public function createLeague(CreateLeague $createLeague, Request $request): Response
    {
        $dto = new CreateLeagueData();
        $form = $this->createForm(CreateLeagueForm::class, $dto);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $createLeague->execute($this->getUser()->getUserIdentifier(), $dto);
                $this->addFlash('success', 'League has been created');
            } catch (LeagueException $e) {
                $this->addFlash('error', $e->getMessage());
            } catch (ParticipantNotFoundException) {
                // @todo we should log this
                $this->addFlash('error', 'Internal error');

            }

            return $this->redirectToRoute('app.user.dashboard');
        }

        return $this->render('user/create-league.html.twig', [
            'form' => $form->createView()
        ]);
    }
}