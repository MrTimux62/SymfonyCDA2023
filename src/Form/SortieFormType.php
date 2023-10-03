<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\Lieu;
use App\Entity\Sortie;
use App\Entity\Ville;
use Doctrine\DBAL\Types\DateTimeType;
use Doctrine\DBAL\Types\DateType;
use Doctrine\DBAL\Types\IntegerType;
use Doctrine\DBAL\Types\TextType;
use Doctrine\DBAL\Types\TimeType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class SortieFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom de la sortie :',
                'attr' => ['maxlength' => 150],
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
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez renseigner une date et une heure.'
                    ]),
                ]
            ])
            ->add('duree', TimeType::class, [
                'label' => 'Durée :',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez renseigner une durée.'
                    ]),
                ]
            ])
            ->add('dateLimiteInscription', DateType::class, [
                'label' => 'Date limite d\'inscription :',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez renseigner une date limite d\'inscription.'
                    ]),
                ]
            ])
            ->add('nbInscriptionsMax', IntegerType::class, [
                'label' => 'Nombre de places :',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez renseigner un nombre de places.'
                    ]),
                ]
            ])
            ->add('infosSortie', TextType::class, [
                'label' => 'Description et infos :',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez renseigner une description.'
                    ]),
                ]
            ])
            ->add('lieu', Lieu::class, [
                'label' => 'Lieu :',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez renseigner un lieu.'
                    ]),
                ]
            ])
            ->add('campus', Campus::class, [
                'label' => 'Campus :',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez renseigner un campus.'
                    ])
                ]
            ])
            ->add('ville', Ville::class, [
                'mapped' => false,
                'label' => 'Ville :',
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
