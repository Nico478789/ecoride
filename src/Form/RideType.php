<?php

namespace App\Form;

use App\Entity\Car;
use App\Entity\Ride;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RideType extends AbstractType
{
    public function __construct(
        private Security $security,
    ) {}

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder
            ->add('whereFrom', TextType::class, [
                'label' => 'D\'où partez vous ?',
            ])
            ->add('whereTo', TextType::class, [
                'label' => 'Où allez vous ?',
            ])
            ->add('departure_time', null, [
                'label' => 'Date et heure de départ',
                'widget' => 'single_text'
            ])
            ->add('arrival_time', null, [
                'label' => 'Date et heure d\'arrivée estimée',
                'widget' => 'single_text'
            ])
            ->add('number_of_seats', NumberType::class, [
                'label' => 'Combien de places disponibles ?',
            ])
            ->add('price', NumberType::class, [
                'label' => 'Prix pour un passager ?',
            ])
            ->add('car', EntityType::class, [
                'label' => 'Sélectionnez votre voiture',
                'class' => Car::class,
                'query_builder' => function (EntityRepository $er): QueryBuilder {
                    return $er->createQueryBuilder('c')
                        ->where('c.driver = :userId')
                        ->setParameter('userId', $this->security->getUser());
                },
                'choice_label' => 'brand_name',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Ride::class,
        ]);
    }
}
