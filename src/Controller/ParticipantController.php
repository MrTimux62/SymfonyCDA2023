<?php

namespace App\Controller;

use App\Form\EditProfileType;
use App\Repository\ParticipantRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ParticipantController extends AbstractController
{
    /**
     * @Route("/profil", name="profil")
     */
    public function index(): Response
    {
        return $this->render('participant/index.html.twig');
    }

    /**
     * @Route("/profil/{id}", name="profil_view")
     */
    public function viewProfil($id, ParticipantRepository $participantRepository): Response
    {
        $participant = $participantRepository->find($id);

        if ($participant === null) {
            return new Response('Ce participant n\'existe pas.', Response::HTTP_NOT_FOUND);
        }

        return $this->render('participant/view.html.twig', [
            'participant' => $participant
        ]);
    }


    /**
     * @Route("modifier", name="profil_modifier")
     */
    public function editProfil(Request $request): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        $user = $this->getUser();
        $form = $this->createForm(EditProfileType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            $this->addFlash('success', 'Profil modifié !');
            return $this->redirectToRoute('profil');
        }
        return $this->render('participant/edit.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
