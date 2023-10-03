<?php

namespace App\Controller;

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
     * @Route("/lieu/getLieuListByVille", name="get_lieu_list_by_ville", methods={"GET"})
     */
    public function getLieuListByVille(Request $request): JsonResponse
    {
        $villeId = $request->query->get('ville_id');
        $lieux = $this->lieuRepository->findBy(['ville' => $villeId]);
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
     * @Route("/lieu/getRueLatitudeLongitude", name="get_rue_latitude_longitude", methods={"GET"})
     */
    public function getRueLatitudeLongitude(Request $request): JsonResponse
    {
        $lieuId = $request->query->get('lieu_id');
        $lieu = $this->lieuRepository->find($lieuId);
        $lieuData = [
            'rue' => $lieu->getRue(),
            'latitude' => $lieu->getLatitude(),
            'longitude' => $lieu->getLongitude()
        ];
        return new JsonResponse($lieuData);
    }
}
