<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SortieController extends AbstractController
{
    /**
     * @Route("/sortie/detail", name="sortie_detail")
     */
    public function detail(): Response
    {
        return $this->render('sortie/detail.html.twig', [
            'controller_name' => 'SortieController',
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
