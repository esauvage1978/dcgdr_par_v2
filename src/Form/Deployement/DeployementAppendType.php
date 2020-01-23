<?php

namespace App\Form\Deployement;

use App\Entity\Deployement;
use App\Form\AppTypeAbstract;
use App\Form\File\DeployementFileType;
use App\Form\File\DeployementLinkType;
use App\Form\IndicatorValue\IndicatorValueType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DeployementAppendType extends AppTypeAbstract
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->buildFormWriters($builder);
        $this->buildFormReaders($builder);
        
        return $builder
            ->add('showat', DateType::class, [
                self::REQUIRED => false,
                'widget' => 'single_text',
            ])
            ->add('endat', DateType::class, [
                self::REQUIRED => false,
                'widget' => 'single_text',
            ])
            ->add('indicatorvalues', CollectionType::class, [
                'entry_type' => IndicatorValueType::class,
                'allow_add' => false,
                'allow_delete' => false,
            ])
            ->add('deployementFiles', CollectionType::class, [
                'entry_type' => DeployementFileType::class,
                'allow_add' => true,
                'allow_delete' => true,
            ])
            ->add('deployementLinks', CollectionType::class, [
                'entry_type' => DeployementLinkType::class,
                'allow_add' => true,
                'allow_delete' => true,
            ]);
            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Deployement::class,
        ]);
    }
}
