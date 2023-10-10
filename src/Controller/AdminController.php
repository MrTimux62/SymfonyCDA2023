<?php

namespace App\Controller;

use App\Entity\Lieu;
use App\Entity\Participant;
use App\Entity\Sortie;
use App\Form\RegistrationFormType;
use App\Repository\LieuRepository;
use App\Repository\ParticipantRepository;
use App\Repository\SortieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    private $participantRepository;
    private $sortieRepository;
    private $lieuRepository;
    private $entityManager;

    public function __construct(ParticipantRepository $participantRepository,
                                SortieRepository $sortieRepository,
                                LieuRepository $lieuRepository,
                                EntityManagerInterface $entityManager)
    {
        $this->participantRepository = $participantRepository;
        $this->sortieRepository = $sortieRepository;
        $this->lieuRepository = $lieuRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/admin", name="admin_home")
     */
    public function home(Request $request, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        if ($this->getUser())
        {
            if (!in_array('ROLE_ADMIN', $this->getUser()->getRoles(), true)) {
                return new Response('Vous ne pouvez pas accéder à cette page.', Response::HTTP_FORBIDDEN);
            }
        } else {
            return $this->redirectToRoute('app_login');
        }

        $user = new Participant();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        // Si inscription utilisateur
        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            // Default Value
            $user->setIsActif(true);
            $user->setRoles(['ROLE_USER']);

            $this->entityManager->persist($user);
            $this->entityManager->flush();
        }

        $participants = $this->participantRepository->findAll();
        $sorties = $this->sortieRepository->findAll();
        $lieux = $this->lieuRepository->findAll();

        return $this->render('admin/home.html.twig', [
            'participants' => $participants,
            'sorties' => $sorties,
            'lieux' => $lieux,
            'registrationForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/sortie/delete/{id}", name="admin_sortieDelete")
     */
    public function sortieDelete(Sortie $sortie): Response
    {
        if ($this->getUser())
        {
            if (!in_array('ROLE_ADMIN', $this->getUser()->getRoles(), true)) {
                return new Response('Vous ne pouvez pas accéder à cette page.', Response::HTTP_FORBIDDEN);
            }
        } else {
            return $this->redirectToRoute('app_login');
        }

        if ($sortie === null) {
            return new Response('Cette sortie n\'existe pas.', Response::HTTP_NOT_FOUND);
        }
        $this->sortieRepository->remove($sortie, true);
        $this->addFlash('warning', 'Sortie supprimée avec succès !');
        return $this->redirectToRoute('admin_home');
    }

    /**
     * @Route("/admin/participant/delete/{id}", name="admin_participantDelete")
     */
    public function participantDelete(Participant $participant): Response
    {
        if ($this->getUser())
        {
            if (!in_array('ROLE_ADMIN', $this->getUser()->getRoles(), true)) {
                return new Response('Vous ne pouvez pas accéder à cette page.', Response::HTTP_FORBIDDEN);
            }
        } else {
            return $this->redirectToRoute('app_login');
        }

        if ($participant === null) {
            return new Response('Ce participant n\'existe pas.', Response::HTTP_NOT_FOUND);
        }
        $this->participantRepository->remove($participant, true);
        $this->addFlash('warning', 'Participant supprimé avec succès !');
        return $this->redirectToRoute('admin_home');
    }

    /**
     * @Route("/admin/lieu/delete/{id}", name="admin_lieuDelete")
     */
    public function lieuDelete(Lieu $lieu): Response
    {
        if ($this->getUser())
        {
            if (!in_array('ROLE_ADMIN', $this->getUser()->getRoles(), true)) {
                return new Response('Vous ne pouvez pas accéder à cette page.', Response::HTTP_FORBIDDEN);
            }
        } else {
            return $this->redirectToRoute('app_login');
        }

        if ($lieu === null) {
            return new Response('Ce lieu n\'existe pas.', Response::HTTP_NOT_FOUND);
        }
        $this->lieuRepository->remove($lieu, true);
        $this->addFlash('warning', 'Lieu supprimé avec succès !');
        return $this->redirectToRoute('admin_home');
    }

    /**
     * @Route("/admin/participant/switch/{id}", name="admin_participantSwitch")
     */
    public function participantSwitch(Participant $participant): Response
    {
        if ($this->getUser())
        {
            if (!in_array('ROLE_ADMIN', $this->getUser()->getRoles(), true)) {
                return new Response('Vous ne pouvez pas accèder à cette page.', Response::HTTP_FORBIDDEN);
            }
        } else {
            return $this->redirectToRoute('app_login');
        }

        if ($participant === null) {
            return new Response('Ce participant n\'existe pas.', Response::HTTP_NOT_FOUND);
        }
        if ($participant->isActif()) {
            $participant->setIsActif(false);
            $this->addFlash('warning', 'Participant désactivé avec succès !');
        } else {
            $participant->setIsActif(true);
            $this->addFlash('warning', 'Participant activé avec succès !');
        }
        $this->participantRepository->add($participant, true);

        return $this->redirectToRoute('admin_home');
    }
}
