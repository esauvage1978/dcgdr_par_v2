<?php

namespace App\Form\Indicator;

use App\Entity\Indicator;
use App\Form\AppTypeAbstract;
use App\Indicator\IndicatorData;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IndicatorType extends AppTypeAbstract
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->buildFormNameEnableContent($builder);

        return $builder
            ->add('goal', TextType::class, [
                self::REQUIRED => false,
            ])
            ->add('value', TextType::class, [
                self::REQUIRED => false,
            ])
            ->add('indicatortype',ChoiceType::class,
                ['choices' => [
                    IndicatorData::getFullNameOfIndicator(IndicatorData::BINAIRE) => IndicatorData::BINAIRE,
                    IndicatorData::getFullNameOfIndicator(IndicatorData::BINAIRE_OUI) => IndicatorData::BINAIRE_OUI,
                    IndicatorData::getFullNameOfIndicator(IndicatorData::BINAIRE_NON) => IndicatorData::BINAIRE_NON,
                    IndicatorData::getFullNameOfIndicator(IndicatorData::QUALITATIF_PALIER_5) => IndicatorData::QUALITATIF_PALIER_5,
                    IndicatorData::getFullNameOfIndicator(IndicatorData::QUALITATIF) => IndicatorData::QUALITATIF,
                    IndicatorData::getFullNameOfIndicator(IndicatorData::QUANTITATIF) => IndicatorData::QUANTITATIF,
                ]])
            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Indicator::class,
        ]);
    }
}
