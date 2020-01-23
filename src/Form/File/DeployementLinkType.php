<?php

namespace App\Form\File;

use App\Entity\ActionLink;
use App\Entity\DeployementFile;
use App\Entity\DeployementLink;
use App\Form\AppTypeAbstract;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DeployementLinkType extends AppTypeAbstract
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class,
                [
                    self::LABEL => 'titre',
                    self::REQUIRED => false
                ])
            ->add('link', UrlType::class,
                [
                    'label' => 'adresse',
                    'required' => false
                ])
            ->add('comment', hiddenType::class,
                [
                    'label' => 'date',
                    'required' => false
                ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => DeployementLink::class,
        ]);
    }
}
