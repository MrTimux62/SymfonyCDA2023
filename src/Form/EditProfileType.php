<?php

namespace App\Form;

use App\Entity\Participant;
use PharIo\Manifest\Email;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class EditProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('pseudo', TextType::class, [
                'label' => 'Votre Pseudo :',
                'label_attr' => ['class' => 'form-control'],
                'attr' => [
                    'maxlength' => 100,
                    'class' => 'info-value'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez renseigner un pseudo.'
                    ]),
                    new Length([
                        'max' => 100,
                        'maxMessage' => 'Le pseudo ne doit pas dépasser 100 caractères.'
                    ])
                ]
            ])
            ->add('email', EmailType::class , [
                'label' => 'Adresse mail :',
                'label_attr' => ['class' => 'form-control'],
                'attr' => [
                    'maxlength' => 180,
                    'class' => 'info-value'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez renseigner votre adresse mail.'
                    ]),
                    new Length([
                        'max' => 180,
                        'maxMessage' => 'Le mail ne doit pas dépasser 180 caractères.'
                    ])
                ]
            ])
            ->add('nom', TextType::class , [
                'label' => 'Votre Nom :',
                'label_attr' => ['class' => 'form-control'],
                'attr' => [
                    'maxlength' => 50,
                    'class' => 'info-value'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez renseigner votre nom.'
                    ]),
                    new Length([
                        'max' => 50,
                        'maxMessage' => 'Le nom ne doit pas dépasser 50 caractères.'
                    ])
                ]
            ])
            ->add('prenom', TextType::class, [
                'label' => 'Votre Prénom :',
                'label_attr' => ['class' => 'form-control'],
                'attr' => [
                    'maxlength' => 50,
                    'class' => 'info-value'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez renseigner votre prénom.'
                    ]),
                    new Length([
                        'max' => 50,
                        'maxMessage' => 'Le prénom ne doit pas dépasser 50 caractères.'
                    ])
                ]
            ])
            ->add('telephone', TextType::class, [
                'label' => 'Numéro de téléphone :',
                'label_attr' => ['class' => 'form-control'],
                'attr' => [
                    'maxlength' => 10,
                    'class' => 'info-value'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez renseigner votre numéro de téléphone.'
                    ]),
                    new Length([
                        'max' => 10,
                        'maxMessage' => 'Le numéro ne doit pas dépasser 10 caractères.'
                    ])
                ]
            ])
            ->add('imageFile', FileType::class, [
                'label' => 'Photo de profil',
                'label_attr' => ['class' => 'form-control'],
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new Image([
                        'maxSize' => '1024k',
                        'mimeTypesMessage' => 'Merci d\'utiliser un format d\'image valide.',
                    ])
                ],
            ])
            ->add('Valider', SubmitType::class)
        ;
    }



    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Participant::class,
        ]);
    }
}
