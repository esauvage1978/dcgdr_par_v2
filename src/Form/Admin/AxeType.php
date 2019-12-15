<?php

namespace App\Form\Admin;

use App\Entity\Axe;
use App\Form\AppTypeAbstract;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AxeType extends AppTypeAbstract
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder = $this->buildFormNameEnableContent($builder);

        $builder
            ->add('archiving', CheckboxType::class,
                [
                    self::LABEL => ' ',
                    self::REQUIRED => false,
                    self::ROW_ATTR => [self::CSS_CLASS => 'test'],
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Axe::class,
        ]);
    }
}
