<?php

namespace App\Controller;

use App\Entity\Ville;
use App\Repository\LieuRepository;
use App\Repository\ParticipantRepository;
use App\Repository\SortieRepository;
use App\Repository\VilleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    private $participantRepository;
    private $sortieRepository;
    private $lieuRepository;

    public function __construct(ParticipantRepository $participantRepository, SortieRepository $sortieRepository, LieuRepository $lieuRepository)
    {
        $this->participantRepository = $participantRepository;
        $this->sortieRepository = $sortieRepository;
        $this->lieuRepository = $lieuRepository;
    }

    /**
     * @Route("/admin", name="admin_home")
     */
    public function home(): Response
    {

        if ($this->getUser())
        {
            if (!in_array('ROLE_ADMIN', $this->getUser()->getRoles(), true)) {
                return new Response('Vous ne pouvez pas accèder à cette page.', Response::HTTP_FORBIDDEN);
            }
        } else {
            return new Response('Vous ne pouvez pas accèder à cette page.', Response::HTTP_FORBIDDEN);
        }

        $participants = $this->participantRepository->findAll();
        $sorties = $this->sortieRepository->findAll();
        $lieux = $this->lieuRepository->findAll();

        return $this->render('admin/home.html.twig', [
            'participants' => $participants,
            'sorties' => $sorties,
            'lieux' => $lieux
        ]);
    }

    /**
     * @Route("/admin/sortie/delete", name="admin_sortie_delete")
     */
    public function sortieDelete(Request $request): Response
    {

        if ($this->getUser())
        {
            if (!in_array('ROLE_ADMIN', $this->getUser()->getRoles(), true)) {
                return new Response('Vous ne pouvez pas accèder à cette page.', Response::HTTP_FORBIDDEN);
            }
        } else {
            return new Response('Vous ne pouvez pas accèder à cette page.', Response::HTTP_FORBIDDEN);
        }

        $sortie = $this->sortieRepository->find($request->query->get('sortie_id'));
        if ($sortie === null) {
            return new Response('Cette sortie n\'existe pas.', Response::HTTP_NOT_FOUND);
        }
        $this->sortieRepository->remove($sortie, true);
        $this->addFlash('warning', 'Sortie supprimée avec succès !');
        return $this->redirectToRoute('admin_home');
    }

    /**
     * @Route("/admin/participant/delete", name="admin_participant_delete")
     */
    public function participantDelete(Request $request): Response
    {

        if ($this->getUser())
        {
            if (!in_array('ROLE_ADMIN', $this->getUser()->getRoles(), true)) {
                return new Response('Vous ne pouvez pas accèder à cette page.', Response::HTTP_FORBIDDEN);
            }
        } else {
            return new Response('Vous ne pouvez pas accèder à cette page.', Response::HTTP_FORBIDDEN);
        }

        $participant = $this->participantRepository->find($request->query->get('participant_id'));
        if ($participant === null) {
            return new Response('Ce participant n\'existe pas.', Response::HTTP_NOT_FOUND);
        }
        $this->participantRepository->remove($participant, true);
        $this->addFlash('warning', 'Participant supprimée avec succès !');
        return $this->redirectToRoute('admin_home');
    }

    /**
     * @Route("/admin/participant/switch", name="admin_participant_switch")
     */
    public function participantSwitch(Request $request): Response
    {

        if ($this->getUser())
        {
            if (!in_array('ROLE_ADMIN', $this->getUser()->getRoles(), true)) {
                return new Response('Vous ne pouvez pas accèder à cette page.', Response::HTTP_FORBIDDEN);
            }
        } else {
            return new Response('Vous ne pouvez pas accèder à cette page.', Response::HTTP_FORBIDDEN);
        }

        $participant = $this->participantRepository->find($request->query->get('participant_id'));
        if ($participant === null) {
            return new Response('Ce participant n\'existe pas.', Response::HTTP_NOT_FOUND);
        }
        if ($participant->isActif()) {
            $participant->setIsActif(false);
            $this->addFlash('warning', 'Participant désactivé avec succès !');
        } else {
            $participant->setIsActif(true);
            $this->addFlash('warning', 'Participant activé avec succès !');
        }
        $this->participantRepository->add($participant, true);

        return $this->redirectToRoute('admin_home');
    }
}
