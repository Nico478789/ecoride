<?php

namespace App\Form;

use App\Entity\Car;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CarType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('brand_name', TextType::class, [
                'label' => 'Marque',
            ])
            ->add('model_name', TextType::class, [
                'label' => 'Modèle',
            ])
            ->add('color', TextType::class, [
                'label' => 'Couleur',
            ])
            ->add('registration_number', TextType::class, [
                'label' => 'Numéro d\'immatriculation',
            ])
            ->add('first_registration_date', DateType::class, [
                'label' => 'Date de première immatriculation',
            ])
            ->add('electric', CheckboxType::class, [
                'label'    => 'Voiture électrique',
                'required' => false,
            ]);
            //             ->add('driver', EntityType::class, [
            //                 'class' => User::class,
            // 'choice_label' => 'id',
            //             ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Car::class,
        ]);
    }
}
