<?php

namespace App\Controller;

use App\Repository\CampusRepository;
use App\Entity\Sortie;
use App\Form\SortieFormType;
use App\Repository\EtatRepository;
use App\Repository\SortieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SortieController extends AbstractController
{
    private $sortieRepository;
    private $campusRepository;
    private $etatRepository;

    public function __construct(SortieRepository $sortieRepository,
                                CampusRepository $campusRepository,
                                EtatRepository $etatRepository)
    {
        $this->sortieRepository = $sortieRepository;
        $this->campusRepository = $campusRepository;
        $this->etatRepository = $etatRepository;
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
            $sortie->setParticipantOrganisateur($this->getUser());
            $sortie->setEtat($this->etatRepository->findOneBy(['libelle' => 'Créée']));
            $this->sortieRepository->add($sortie, true);
            $this->addFlash('success', 'Sortie enregistrée avec succès !');
            return $this->redirectToRoute('sortie_list');
        }

        return $this->render('sortie/create.html.twig', [
            'sortieForm' => $form->createView(),
        ]);
    }
}
