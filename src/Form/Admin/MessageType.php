<?php

namespace App\Form\Admin;

use App\Entity\Message;
use App\Form\AppTypeAbstract;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MessageType extends AppTypeAbstract
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name',ChoiceType::class,
                ['choices' => [
                    'Administration' => 'admin',
                    'Documentation' => 'doc',
                    'Page d\'accueil'=>'home',
                    'Tableau de bord' => 'dashboard',

                ]])
            ->add('enable', CheckboxType::class,
                [
                    self::LABEL => ' ',
                    self::REQUIRED => false,
                ])
            ->add('content', TextareaType::class, [
                self::LABEL => 'Description',
                self::REQUIRED => false,
                self::ATTR => [self::ROWS => 3, self::CSS_CLASS => 'textarea'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Message::class,
        ]);
    }
}
