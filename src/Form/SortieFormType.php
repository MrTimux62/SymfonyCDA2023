<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\Lieu;
use App\Entity\Sortie;
use App\Entity\Ville;
use App\Repository\CampusRepository;
use App\Repository\LieuRepository;
use App\Repository\VilleRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class SortieFormType extends AbstractType
{
    private $campusRepository;
    private $villeRepository;
    private $lieuRepository;

    public function __construct(CampusRepository $campusRepository, VilleRepository $villeRepository, LieuRepository $lieuRepository)
    {
        $this->campusRepository = $campusRepository;
        $this->villeRepository = $villeRepository;
        $this->lieuRepository = $lieuRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom de la sortie :',
                'label_attr' => ['class' => 'info-label'],
                'attr' => [
                    'maxlength' => 150,
                    'class' => 'info-value'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez renseigner un nom.'
                    ]),
                    new Length([
                        'max' => 150,
                        'maxMessage' => 'Le nom ne doit pas dépasser 150 caractères.'
                    ])
                ]
            ])
            ->add('dateHeureDebut', DateTimeType::class, [
                'label' => 'Date et heure de la sortie :',
                'label_attr' => ['class' => 'info-label'],
                'attr' => ['class' => 'info-value'],
                'widget' => 'single_text',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez renseigner une date et une heure.'
                    ]),
                ]
            ])
            ->add('duree', TimeType::class, [
                // TimeType alors qu'il faudrait un IntegerType selon la maquette :
                // IntegerType pose souci à la soumission du formulaire (le champ est de type time dans la DB)
                'label' => 'Durée :',
                'label_attr' => ['class' => 'info-label'],
                'attr' => [
                    'class' => 'info-value',
                    'min' => 0
                ],
                'help' => 'minutes',
                'widget' => 'single_text',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez renseigner une durée.'
                    ]),
                ]
            ])
            ->add('dateLimiteInscription', DateType::class, [
                'label' => 'Date limite d\'inscription :',
                'label_attr' => ['class' => 'info-label'],
                'attr' => ['class' => 'info-value'],
                'widget' => 'single_text',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez renseigner une date limite d\'inscription.'
                    ]),
                ]
            ])
            ->add('nbInscriptionsMax', IntegerType::class, [
                'label' => 'Nombre de places :',
                'label_attr' => ['class' => 'info-label'],
                'attr' => [
                    'class' => 'info-value',
                    'min' => 0
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez renseigner un nombre de places.'
                    ]),
                ]
            ])
            ->add('infosSortie', TextareaType::class, [
                'label' => 'Description et infos :',
                'label_attr' => ['class' => 'info-label'],
                'attr' => [
                    'class' => 'info-value',
                    'rows' => 5,
                    'cols' => 40
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez renseigner une description.'
                    ]),
                ]
            ])
            ->add('lieu', EntityType::class, [
                'class' => Lieu::class,
                'choice_label' => 'nom',
                'label' => 'Lieu :',
                'label_attr' => ['class' => 'info-label'],
                'attr' => ['class' => 'info-value'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez renseigner un lieu.'
                    ]),
                ]
            ])
            ->add('campus', EntityType::class, [
                'class' => Campus::class,
                'choice_label' => 'nom',
                'label' => 'Campus :',
                'label_attr' => ['class' => 'info-label'],
                'attr' => ['class' => 'info-value'],
                'choices' => $this->campusRepository->findAll(),
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez renseigner un campus.'
                    ])
                ]
            ])
            ->add('ville', EntityType::class, [
                'class' => Ville::class,
                'choice_label' => 'nom',
                'mapped' => false,
                'label' => 'Ville :',
                'label_attr' => ['class' => 'info-label'],
                'attr' => ['class' => 'info-value'],
                'choices' => $this->villeRepository->findAll(),
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez renseigner une ville.'
                    ])
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
