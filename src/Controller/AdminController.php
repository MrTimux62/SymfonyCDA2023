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
    public function list(): Response
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
}
