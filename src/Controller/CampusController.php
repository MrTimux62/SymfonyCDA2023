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
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
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
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
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
     * @Route("/campus/edit", name="campus_edit")
     */
    public function edit(Request $request, CampusRepository $campusRepository, EntityManagerInterface $entityManager): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        $campus = $campusRepository->find($request->query->get('campus_id'));
        $campus->setNom($request->query->get('campus_name'));

        $entityManager->persist($campus);
        $entityManager->flush();

        return $this->json([
            'success' => true,
            'campus_name' => $campus->getNom()
        ]);
    }

    /**
     * @Route("/campus/remove", name="campus_remove")
     */
    public function remove(Request $request, CampusRepository $campusRepository, EntityManagerInterface $entityManager): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        $camp = $campusRepository->find($request->query->get('campus_id'));
        $campusRepository->remove($camp, true);

        return $this->json([
            'success' => true
        ]);
    }
}
