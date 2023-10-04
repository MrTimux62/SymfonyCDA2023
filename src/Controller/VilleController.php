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

class VilleController extends AbstractController
{
    private $villeRepository;

    public function __construct(VilleRepository $villeRepository)
    {
        $this->villeRepository = $villeRepository;
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
     * @Route("/ville/getCodePostal", name="get_code_postal", methods={"GET"})
     */
    public function getCodePostal(Request $request): JsonResponse
    {
        $villeId = $request->query->get('ville_id');
        $codePostal = $this->villeRepository->find($villeId)->getCodePostal();
        return new JsonResponse($codePostal);
    }

    /**
     * @Route("/ville/create", name="ville_create")
     */
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $ville = new Ville();
        $ville->setNom($request->query->get('city_name'))->setCodePostal($request->query->get('city_postalCode'));

        $entityManager->persist($ville);
        $entityManager->flush();

        return $this->json([
            'success' => true,
            'city' => $ville
        ]);
    }

    /**
     * @Route("/ville/edit", name="ville_edit")
     */
    public function edit(Request $request, VilleRepository $villeRepository, EntityManagerInterface $entityManager): Response
    {
        $ville = $villeRepository->find($request->query->get('city_id'));
        $ville->setNom($request->query->get('city_name'))->setCodePostal($request->query->get('city_postalCode'));

        $entityManager->persist($ville);
        $entityManager->flush();

        return $this->json([
            'success' => true,
            'city_name' => $ville->getNom(),
            'city_postalCode' => $ville->getCodePostal(),
        ]);
    }

    /**
     * @Route("/ville/remove", name="ville_remove")
     */
    public function remove(Request $request, VilleRepository $villeRepository, EntityManagerInterface $entityManager): Response // WIP
    {
        $ville = $villeRepository->find($request->query->get('city_id'));
        $villeRepository->remove($ville, true);

        return $this->json([
            'success' => true
        ]);
    }
}
