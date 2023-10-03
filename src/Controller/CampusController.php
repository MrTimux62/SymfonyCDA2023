<?php

namespace App\Controller;

use App\Entity\Campus;
use App\Repository\CampusRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CampusController extends AbstractController
{
    /**
     * @Route("/campus", name="campus_list")
     */
    public function list(CampusRepository $campusRepository): Response
    {
        $campus = $campusRepository->findAll();

        return $this->render('campus/list.html.twig', [
            'campus' => $campus
        ]);
    }

    /**
     * @Route("/campus/create", name="campus_create")
     */
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $campus = new Campus();
        $campus->setNom($request->query->get('campus_name'));

        $entityManager->persist($campus);
        $entityManager->flush();

        return $this->json([
            'success' => true,
            'campus' => $campus
        ]);
    }

    /**
     * @Route("/campus/remove", name="campus_remove")
     */
    public function remove(Request $request, CampusRepository $campusRepository, EntityManagerInterface $entityManager): Response // WIP
    {
        $camp = $campusRepository->find($request->query->get('campus_id'));
        $entityManager->remove($camp);
        $entityManager->flush();

        $campus = $campusRepository->findAll();

        return $this->render('campus/list.html.twig', [
            'campus' => $campus
        ]);
    }
}
