<?php

namespace App\Form\Admin;

use App\Entity\Corbeille;
use App\Form\AppTypeAbstract;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CorbeilleType extends AppTypeAbstract
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder = $this->buildFormGenerique($builder);
        $builder = $this->buildFormOrganisme($builder);
    }

    public function buildFormGenerique(FormBuilderInterface $builder): FormBuilderInterface
    {
        $builder = $this->buildFormNameEnableContent($builder);
        $builder = $this->buildFormUsers($builder);
        $builder
            ->add('showDefault', CheckboxType::class, [
                self::LABEL => ' ',
                self::REQUIRED => false,
            ])
            ->add('showRead', CheckboxType::class, [
                self::LABEL => ' ',
                self::REQUIRED => false,
            ])
            ->add('showWrite', CheckboxType::class, [
                self::LABEL => ' ',
                self::REQUIRED => false,
            ])
            ->add('showValidate', CheckboxType::class, [
                self::LABEL => ' ',
                self::REQUIRED => false,
                'row_attr' => ['class' => 'test'],
            ]);

        return $builder;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Corbeille::class,
        ]);
    }
}
