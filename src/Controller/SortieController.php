<?php

namespace App\Controller;

use App\Repository\CampusRepository;
use App\Entity\Sortie;
use App\Form\SortieFormType;
use App\Repository\EtatRepository;
use App\Repository\ParticipantRepository;
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

        return $this->render('sortie/create_edit.html.twig', [
            'sortieForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/sortie/edit", name="sortie_edit")
     */
    public function edit(Request $request): Response
    {
        $sortie =  $this->sortieRepository->find($request->query->get('sortie_id'));
        if ($this->getUser()->getId() !== $sortie->getParticipantOrganisateur()->getId()
            || $sortie->getEtat()->getLibelle() !== 'Créée') {
            return new Response('Vous ne pouvez pas modifier cette sortie.', Response::HTTP_FORBIDDEN);
        }
        $form = $this->createForm(SortieFormType::class, $sortie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($sortie);
            $em->flush();
            $this->addFlash('success', 'Sortie modifiée avec succès !');
            return $this->redirectToRoute('sortie_list');
        }

        return $this->render('sortie/create_edit.html.twig', [
            'sortieForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/sortie/desister", name="sortie_desister")
     */
    public function desister(Request $request): Response
    {
        $sortie = $this->sortieRepository->find($request->query->get('sortie_id'));
        $sortie->removeParticipant($this->getUser());
        $this->sortieRepository->add($sortie, true);
        $this->addFlash('warning', 'Désistement pris en compte');

        return $this->redirectToRoute('sortie_list');
    }

    /**
     * @Route("/sortie/inscription", name="sortie_inscription")
     */
    public function inscription(Request $request): Response
    {
        $sortie = $this->sortieRepository->find($request->query->get('sortie_id'));
        $sortie->addParticipant($this->getUser());
        $this->sortieRepository->add($sortie, true);
        $this->addFlash('success', 'Inscrit avec succès !');

        return $this->redirectToRoute('sortie_list');
    }
}
