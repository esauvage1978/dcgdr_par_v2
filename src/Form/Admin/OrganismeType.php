<?php

namespace App\Form\Admin;

use App\Entity\Organisme;
use App\Form\AppTypeAbstract;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrganismeType extends AppTypeAbstract
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder = $this->buildFormNameEnableContent($builder);
        $builder = $this->buildFormUsers($builder);

        $builder
            ->add('ref', TextType::class, [
                self::LABEL => 'Référence',
                self::REQUIRED => true,
            ])
            ->add('alterable', CheckboxType::class,
                [
                    self::LABEL => ' ',
                    self::REQUIRED => false,
                    'row_attr' => ['class' => 'test'],
                ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Organisme::class,
        ]);
    }
}
