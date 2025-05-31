<?php

namespace App\Form;

use App\Entity\Ride;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('whereFrom', TextType::class, [
                'label' => 'D\'où partez vous?',
                'required' => true,
                'attr' => [
                    'placeholder' => 'ville',
                ],
            ])
            ->add('whereTo', TextType::class, [
                'label' => 'Où allez vous?',
                'required' => true,
                'attr' => [
                    'placeholder' => 'ville',
                ],
            ])
            ->add('DepartureTime', DateType::class, [
                'label' => 'A quelle date ?',
                'required' => true,
                'attr' => [
                    'placeholder' => 'jj/mm/aaaa',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Ride::class,
        ]);
    }
}
