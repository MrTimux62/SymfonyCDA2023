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
}
