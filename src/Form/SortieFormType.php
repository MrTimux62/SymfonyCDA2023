<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\Lieu;
use App\Entity\Sortie;
use App\Entity\Ville;
use App\Repository\CampusRepository;
use App\Repository\VilleRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SortieFormType extends AbstractType
{
    private $campusRepository;
    private $villeRepository;

    public function __construct(CampusRepository $campusRepository, VilleRepository $villeRepository)
    {
        $this->campusRepository = $campusRepository;
        $this->villeRepository = $villeRepository;
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
                ]
            ])
            ->add('dateHeureDebut', DateTimeType::class, [
                'label' => 'Date et heure de la sortie :',
                'label_attr' => ['class' => 'info-label'],
                'attr' => ['class' => 'info-value'],
                'widget' => 'single_text'
            ])
            ->add('duree', IntegerType::class, [
                'label' => 'Durée :',
                'label_attr' => ['class' => 'info-label'],
                'attr' => [
                    'class' => 'info-value',
                    'min' => 0
                ],
                'help' => 'minutes',
            ])
            ->add('dateLimiteInscription', DateType::class, [
                'label' => 'Date limite d\'inscription :',
                'label_attr' => ['class' => 'info-label'],
                'attr' => ['class' => 'info-value'],
                'widget' => 'single_text'
            ])
            ->add('nbInscriptionsMax', IntegerType::class, [
                'label' => 'Nombre de places :',
                'label_attr' => ['class' => 'info-label'],
                'attr' => [
                    'class' => 'info-value',
                    'min' => 0
                ]
            ])
            ->add('infosSortie', TextareaType::class, [
                'label' => 'Description et infos :',
                'label_attr' => ['class' => 'info-label'],
                'attr' => [
                    'class' => 'info-value',
                    'rows' => 5,
                    'cols' => 40
                ]
            ])
            ->add('lieu', EntityType::class, [
                'class' => Lieu::class,
                'choice_label' => 'nom',
                'label' => 'Lieu :',
                'label_attr' => ['class' => 'info-label'],
                'attr' => ['class' => 'info-value']
            ])
            ->add('campus', EntityType::class, [
                'class' => Campus::class,
                'choice_label' => 'nom',
                'label' => 'Campus :',
                'label_attr' => ['class' => 'info-label'],
                'attr' => ['class' => 'info-value'],
                'choices' => $this->campusRepository->findAll()
            ])
            ->add('ville', EntityType::class, [
                'class' => Ville::class,
                'choice_label' => 'nom',
                'mapped' => false,
                'label' => 'Ville :',
                'label_attr' => ['class' => 'info-label'],
                'attr' => ['class' => 'info-value'],
                'choices' => $this->villeRepository->findAll()
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
