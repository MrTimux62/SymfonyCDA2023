<?php

namespace App\Controller;

use App\Entity\Ville;
use App\Repository\VilleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VilleController extends AbstractController
{
    /**
     * @Route("/ville", name="ville_list")
     */
    public function list(VilleRepository $villeRepository): Response
    {
        $villes = $villeRepository->findAll();

        return $this->render('ville/list.html.twig', [
            'villes' => $villes
        ]);
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
}
