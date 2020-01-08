<?php

namespace App\Form\Deployement;

use App\Entity\Deployement;
use App\Form\AppTypeAbstract;
use App\Form\IndicatorValue\IndicatorValueType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DeployementAppendType extends AppTypeAbstract
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        return $builder
            ->add('showat', DateType::class, [
                self::REQUIRED => false,
                'widget' => 'single_text',
            ])
            ->add('endat', DateType::class, [
                self::REQUIRED => false,
                'widget' => 'single_text',
            ])
            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Deployement::class,
        ]);
    }
}
