<?php

namespace App\Controller;

use App\Repository\VilleRepository;
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
}
