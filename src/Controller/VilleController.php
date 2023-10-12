<?php

namespace App\Controller;

use App\Entity\Ville;
use App\Repository\VilleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Flex\Update\RecipeUpdate;

class VilleController extends AbstractController
{
    private $villeRepository;
    private $entityManager;

    public function __construct(VilleRepository $villeRepository, EntityManagerInterface $entityManager)
    {
        $this->villeRepository = $villeRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/ville", name="ville_list")
     */
    public function list(): Response
    {
        $villes = $this->villeRepository->findAll();

        return $this->render('ville/list.html.twig', [
            'villes' => $villes
        ]);
    }

    /**
     * @Route("/ville/getCodePostal", name="ville_getCodePostal", methods={"GET"})
     */
    public function getCodePostal(Request $request): JsonResponse
    {
        $codePostal = $this->villeRepository->find($request->query->get('ville_id'))->getCodePostal();
        return new JsonResponse($codePostal);
    }

    /**
     * @Route("/ville/create", name="ville_create")
     */
    public function create(Request $request): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('login');
        }
        $ville = new Ville();
        $ville->setNom($request->query->get('city_name'))->setCodePostal($request->query->get('city_postalCode'));

        $this->entityManager->persist($ville);
        $this->entityManager->flush();

        return $this->json([
            'success' => true,
            'city' => $ville
        ]);
    }

    /**
     * @Route("/ville/edit", name="ville_edit")
     */
    public function edit(Request $request): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('login');
        }

        $ville = $this->villeRepository->find($request->query->get('city_id'));
        $ville->setNom($request->query->get('city_name'))->setCodePostal($request->query->get('city_postalCode'));

        $this->entityManager->persist($ville);
        $this->entityManager->flush();

        return $this->json([
            'success' => true,
            'city_name' => $ville->getNom(),
            'city_postalCode' => $ville->getCodePostal(),
        ]);
    }

    /**
     * @Route("/ville/delete", name="ville_delete")
     */
    public function delete(Request $request): Response // WIP
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('login');
        }

        $ville = $this->villeRepository->find($request->query->get('city_id'));
        $this->villeRepository->remove($ville, true);

        return $this->json([
            'success' => true
        ]);
    }
}
