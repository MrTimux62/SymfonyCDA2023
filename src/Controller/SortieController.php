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
        if (!$this->getUser()) {
            return $this->redirectToRoute('login');
        }
        $sorties = $this->sortieRepository->findAllNonArchive();
        $campus = $this->campusRepository->findAll();

        return $this->render('sortie/list.html.twig', [
            'sorties' => $sorties,
            'campus' => $campus
        ]);
    }
    /**
     * @Route("/sortie/detail/{id}", name="sortie_detail")
     */
    public function detail(Sortie $sortie): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('login');
        }
        if ($sortie === null) {
            return new Response('Cette sortie n\'existe pas.', Response::HTTP_NOT_FOUND);
        }
        if ($sortie->getEtat()->getLibelle() !== 'Ouverte' &&
            ($sortie->getParticipantOrganisateur() !== $this->getUser() ||
            $sortie->getEtat()->getLibelle() === 'Créée' ||
            !$sortie->getParticipants()->contains($this->getUser()))) {
            return new Response('Vous ne pouvez pas afficher cette sortie.', Response::HTTP_FORBIDDEN);
        }

        return $this->render('sortie/detail.html.twig', [
            'sortie' => $sortie,
        ]);
    }

    /**
     * @Route("/sortie/create", name="sortie_create")
     */
    public function create(Request $request): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('login');
        }
        $sortie = new Sortie();
        $form = $this->createForm(SortieFormType::class, $sortie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $sortie->setParticipantOrganisateur($this->getUser());
            $sortie->setEtat($this->etatRepository->findOneBy(['libelle' => 'Créée']));
            $this->sortieRepository->add($sortie, true);
            $this->register(new Request(['sortie_id' => $sortie->getId()]));
            if ($request->request->has('publier')) {
                return $this->publish($sortie);
            }
            $this->addFlash('success', 'Sortie enregistrée avec succès !');
            return $this->redirectToRoute('sortie_list');
        }

        return $this->render('sortie/create_edit.html.twig', [
            'sortieForm' => $form->createView(),
            'sortieId' => null
        ]);
    }

    /**
     * @Route("/sortie/edit/{id}", name="sortie_edit")
     */
    public function edit(Sortie $sortie, Request $request): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('login');
        }
        if ($sortie === null) {
            return new Response('Cette sortie n\'existe pas.', Response::HTTP_NOT_FOUND);
        }
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
            if ($request->request->has('publier')) {
                return $this->publish($sortie);
            }
            $this->addFlash('success', 'Sortie modifiée avec succès !');
            return $this->redirectToRoute('sortie_list');
        }

        return $this->render('sortie/create_edit.html.twig', [
            'sortieForm' => $form->createView(),
            'sortie' => $sortie
        ]);
    }

    /**
     * @Route("/sortie/unsubscribe/", name="sortie_unsubscribe")
     */
    public function unsubscribe(Request $request): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('login');
        }

        $sortie = $this->sortieRepository->find($request->query->get('sortie_id'));
        if ($sortie === null) {
            return new Response('Cette sortie n\'existe pas.', Response::HTTP_NOT_FOUND);
        }
        if ($sortie->getDateLimiteInscription() < getdate()) {
            return $this->redirectToRoute('sortie_list');
        }
        $sortie->removeParticipant($this->getUser());
        $this->sortieRepository->add($sortie, true);
        $this->addFlash('warning', 'Désistement pris en compte');

        return $this->redirectToRoute('sortie_list');
    }

    /**
     * @Route("/sortie/register", name="sortie_register")
     */
    public function register(Request $request): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('login');
        }

        $sortie = $this->sortieRepository->find($request->query->get('sortie_id'));
        if ($sortie === null) {
            return new Response('Cette sortie n\'existe pas.', Response::HTTP_NOT_FOUND);
        }
        if ($sortie->getParticipants()->count() === $sortie->getNbInscriptionsMax() || $sortie->getDateLimiteInscription() < getdate()) {
            return new Response('Vous ne pouvez pas vous inscrire à cette sortie.', Response::HTTP_FORBIDDEN);
        }
        $sortie->addParticipant($this->getUser());
        $this->sortieRepository->add($sortie, true);
        $this->addFlash('success', 'Inscrit avec succès !');

        return $this->redirectToRoute('sortie_list');
    }

    /**
     * @Route("/sortie/publish/{id}", name="sortie_publish")
     */
    public function publish(Sortie $sortie): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('login');
        }
        if ($sortie === null) {
            return new Response('Cette sortie n\'existe pas.', Response::HTTP_NOT_FOUND);
        }
        if ($this->getUser()->getId() !== $sortie->getParticipantOrganisateur()->getId()
            || $sortie->getEtat()->getLibelle() !== 'Créée') {
            return new Response('Vous ne pouvez pas publier cette sortie.', Response::HTTP_FORBIDDEN);
        }
        $sortie->setEtat($this->etatRepository->findOneBy(['libelle' => 'Ouverte']));
        $em = $this->getDoctrine()->getManager();
        $em->persist($sortie);
        $em->flush();
        $this->addFlash('success', 'Sortie publiée avec succès !');
        return $this->redirectToRoute('sortie_list');
    }

    /**
     * @Route("/sortie/delete/{id}", name="sortie_delete")
     */
    public function delete(Sortie $sortie): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('login');
        }
        if ($sortie === null) {
            return new Response('Cette sortie n\'existe pas.', Response::HTTP_NOT_FOUND);
        }
        if ($this->getUser()->getId() !== $sortie->getParticipantOrganisateur()->getId()
            || $sortie->getEtat()->getLibelle() !== 'Créée') {
            return new Response('Vous ne pouvez pas supprimer cette sortie.', Response::HTTP_FORBIDDEN);
        }
        $this->sortieRepository->remove($sortie, true);
        $this->addFlash('warning', 'Sortie supprimée avec succès !');
        return $this->redirectToRoute('sortie_list');
    }
}
