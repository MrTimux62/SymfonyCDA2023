<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Repository\CampusRepository;
use App\Entity\Sortie;
use App\Form\SortieFormType;
use App\Repository\SortieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Time;

class SortieController extends AbstractController
{
    private $sortieRepository;
    private $campusRepository;

    public function __construct(SortieRepository $sortieRepository,
                                CampusRepository $campusRepository)
    {
        $this->sortieRepository = $sortieRepository;
        $this->campusRepository = $campusRepository;
    }
    /**
     * @Route("/", name="sortie_list")
     */
    public function list(): Response
    {
        $sorties = $this->sortieRepository->findAll();
        $campus = $this->campusRepository->findAll();

        return $this->render('sortie/list.html.twig', [
            'sorties' => $sorties,
            'campus' => $campus
        ]);
    }
    /**
     * @Route("/sortie/detail", name="sortie_detail")
     */
    public function detail(Request $request): Response
    {
        $sortie =  $this->sortieRepository->find($request->query->get('sortie_id'));

        return $this->render('sortie/detail.html.twig', [
            'sortie' => $sortie,
        ]);
    }

    /**
     * @Route("/sortie/create", name="sortie_create")
     */
    public function create(Request $request): Response
    {
        $sortie = new Sortie();
        $form = $this->createForm(SortieFormType::class, $sortie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // TODO : setter les champs de la sortie non saisis dans le formulaire, pour que l'enregistrement soit fonctionnel
            // exemple : $sortie->setParticipant() pour l'organisateur, correspondant à l'utilisateur connecté
            $this->sortieRepository->add($sortie, true);
            $this->addFlash('success', 'Sortie enregistrée avec succès !');
            return $this->redirectToRoute('sortie_list');
        }

        return $this->render('sortie/create.html.twig', [
            'sortieForm' => $form->createView(),
        ]);
    }
}
