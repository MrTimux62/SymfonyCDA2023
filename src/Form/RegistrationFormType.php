<?php

namespace App\Form;

use App\Entity\Participant;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Email :',
                'label_attr' => ['class' => 'info-label'],
                'attr' => [
                    'maxlength' => 150,
                    'class' => 'info-value form-control'
                ]
            ])
            ->add('nom', TextType::class, [
                'label' => 'Nom :',
                'label_attr' => ['class' => 'info-label'],
                'attr' => [
                    'maxlength' => 150,
                    'class' => 'info-value form-control'
                ]
            ])
            ->add('prenom', TextType::class, [
                'label' => 'Prénom :',
                'label_attr' => ['class' => 'info-label'],
                'attr' => [
                    'maxlength' => 150,
                    'class' => 'info-value form-control'
                ]
            ])
            ->add('telephone', NumberType::class, [
                'label' => 'Téléphone :',
                'label_attr' => ['class' => 'info-label'],
                'attr' => [
                    'maxlength' => 150,
                    'class' => 'info-value form-control'
                ]
            ])
            ->add('pseudo', TextType::class, [
                'label' => 'Pseudo :',
                'label_attr' => ['class' => 'info-label'],
                'attr' => [
                    'maxlength' => 150,
                    'class' => 'info-value form-control'
                ]
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ]
            ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'label' => 'Mot de passe :',
                'label_attr' => ['class' => 'info-label'],
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password', 'class' => 'info-value form-control'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Participant::class,
        ]);
    }
}
