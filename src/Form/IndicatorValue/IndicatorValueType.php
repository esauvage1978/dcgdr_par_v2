<?php

namespace App\Form\IndicatorValue;

use App\Entity\IndicatorValue;
use App\Form\AppTypeAbstract;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IndicatorValueType extends AppTypeAbstract
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        return $builder
            ->add('goal', TextType::class, [
                self::REQUIRED => false,
            ])
            ->add('value', TextType::class, [
                self::REQUIRED => false,
            ])
            ->add('content', TextareaType::class, [
                self::LABEL => 'Commentaire',
                self::REQUIRED => false,
                self::ATTR => [self::ROWS => 3, self::CSS_CLASS => 'textarea'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => IndicatorValue::class,
        ]);
    }
}
