<?php

namespace App\Form\Profil;

use App\Entity\User;
use App\Form\AppTypeAbstract;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProfilType extends AppTypeAbstract
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                self::LABEL => 'Nom',
                self::REQUIRED => true
            ])
            ->add('email', EmailType::class, [
                self::LABEL => 'Mail',
                self::REQUIRED => true
            ])
            ->add('phone', TextType::class, [
                self::LABEL => 'Téléphone',
                self::REQUIRED => false
            ])
            ->add('content', TextareaType::class, [
                self::LABEL => 'Description',
                self::REQUIRED => false
            ])
;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
