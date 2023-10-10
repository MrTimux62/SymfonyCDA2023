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
    private $entityManager;
    private $campusRepository;
    
    public function __construct(EntityManagerInterface $entityManager, CampusRepository $campusRepository)
    {
        $this->entityManager = $entityManager;
        $this->campusRepository = $campusRepository;
    }
    
    /**
     * @Route("/campus", name="campus_list")
     */
    public function list(): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        $campus = $this->campusRepository->findAll();

        return $this->render('campus/list.html.twig', [
            'campus' => $campus
        ]);
    }

    /**
     * @Route("/campus/create", name="campus_create")
     */
    public function create(Request $request): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        $campus = new Campus();
        $campus->setNom($request->query->get('campus_name'));

        $this->entityManager->persist($campus);
        $this->entityManager->flush();

        return $this->json([
            'success' => true,
            'campus' => $campus
        ]);
    }

    /**
     * @Route("/campus/edit/{id}", name="campus_edit")
     */
    public function edit(Campus $campus, Request $request): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        $campus->setNom($request->query->get('campus_name'));

        $this->entityManager->persist($campus);
        $this->entityManager->flush();

        return $this->json([
            'success' => true,
            'campus_name' => $campus->getNom()
        ]);
    }

    /**
     * @Route("/campus/delete/{id}", name="campus_delete")
     */
    public function delete(Campus $campus): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        $this->campusRepository->remove($campus, true);

        return $this->json([
            'success' => true
        ]);
    }
}
