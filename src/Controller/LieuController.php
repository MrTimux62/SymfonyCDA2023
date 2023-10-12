<?php

namespace App\Controller;

use App\Entity\Lieu;
use App\Entity\Ville;
use App\Repository\LieuRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class LieuController extends AbstractController
{
    private $lieuRepository;

    public function __construct(LieuRepository $lieuRepository)
    {
        $this->lieuRepository = $lieuRepository;
    }

    /**
     * @Route("/lieu/getLieuListByVille", name="lieu_getLieuListByVille", methods={"GET"})
     */
    public function getLieuListByVille(Request $request): JsonResponse
    {
        $lieux = $this->lieuRepository->findBy(['ville' => $request->query->get('ville_id')]);
        $lieuxArray = [];
        foreach ($lieux as $lieu) {
            $lieuxArray[] = [
                'id' => $lieu->getId(),
                'nom' => $lieu->getNom(),
            ];
        }
        return new JsonResponse($lieuxArray);
    }

    /**
     * @Route("/lieu/getRueLatitudeLongitude", name="lieu_getRueLatitudeLongitude", methods={"GET"})
     */
    public function getRueLatitudeLongitude(Request $request): JsonResponse
    {
        $lieu = $this->lieuRepository->find($request->query->get('lieu_id'));
        $lieuData = [
            'rue' => $lieu->getRue(),
            'latitude' => $lieu->getLatitude(),
            'longitude' => $lieu->getLongitude()
        ];
        return new JsonResponse($lieuData);
    }
}
