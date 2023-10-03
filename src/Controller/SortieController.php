<?php

namespace App\Controller;

use App\Repository\CampusRepository;
use App\Repository\SortieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SortieController extends AbstractController
{
    /**
     * @Route("/", name="sortie_list")
     */
    public function list(SortieRepository $sortieRepository, CampusRepository $campusRepository): Response
    {
        $sorties = $sortieRepository->findAll();
        $campus = $campusRepository->findAll();

        return $this->render('sortie/list.html.twig', [
            'sorties' => $sorties,
            'campus' => $campus
        ]);
    }
    /**
     * @Route("/sortie/detail", name="sortie_detail")
     */
    public function detail(SortieRepository $sortieRepository, Request $request): Response
    {
        $sortie =  $sortieRepository->find($request->query->get('sortie_id'));

        return $this->render('sortie/detail.html.twig', [
            'sortie' => $sortie,
        ]);
    }

    /**
     * @Route("/sortie/create", name="sortie_create")
     */
    public function create(): Response
    {
        return $this->render('sortie/create.html.twig', [
            'controller_name' => 'SortieController',
        ]);
    }
}
