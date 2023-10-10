<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Form\EditPasswordType;
use App\Form\EditProfileType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class ParticipantController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/participant", name="participant_index")
     */
    public function index(): Response
    {
        return $this->render('participant/index.html.twig');
    }

    /**
     * @Route("/participant/detail/{id}", name="participant_detail")
     */
    public function detail(Participant $participant): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        if ($participant === null) {
            return new Response('Ce participant n\'existe pas.', Response::HTTP_NOT_FOUND);
        }

        return $this->render('participant/view.html.twig', [
            'participant' => $participant
        ]);
    }

    /**
     * @Route("/participant/edit", name="participant_edit")
     */
    public function edit(Request $request, SluggerInterface $slugger): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        $user = $this->getUser();
        $form = $this->createForm(EditProfileType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('imageFile')->getData();
            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('upload_image'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    $this->addFlash('error', 'Un problème est survenue lors du chargement de votre image.');
                    $this->addFlash('error', $e->getMessage());
                }

                // Before changing the filepath, we delete the old file
                $filename = $user->getImage();
                if ($filename) {
                    $fileRoute = $this->getParameter("upload_image").$filename;
                    $filesystem = new Filesystem();
                    $filesystem->remove($fileRoute);
                }

                $user->setImage($newFilename);
            }
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


    /**
     * @Route("profil/edit/password", name="participant_editPassword")
     */
    public function editPassword(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        $user = $this->getUser();
        $form = $this->createForm(EditPasswordType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $hash = $passwordEncoder->encodePassword($user, $form->get('new_password')->getData());
            $user->setPassword($hash);

            $this->entityManager->persist($user);
            $this->entityManager->flush();

            $this->addFlash('success', 'Mot de passe modifié !');

            return $this->redirectToRoute('profil');
        }

        return $this->render('participant/edit.password.twig', [
            'form' => $form->createView(),
        ]);
    }

}