<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichFileType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nickname', TextType::class, [
                'label' => 'Pseudo',
            ])
            ->add('phone_number', TextType::class, [
                'label' => 'Numéro de téléphone',
            ])
            ->add('imageFile', VichFileType::class, [
                'required' => false,
                'label' => 'Photo de profil',
                'allow_delete' => true,
                'delete_label' => 'Supprimer la photo',
                'asset_helper' => false,
                'download_uri' => false,
            ]);;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
