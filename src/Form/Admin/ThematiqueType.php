<?php

namespace App\Form\Admin;

use App\Entity\Thematique;
use App\Entity\Pole;
use App\Form\AppTypeAbstract;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ThematiqueType extends AppTypeAbstract
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder = $this->buildFormNameEnableContent($builder);

        $builder
            ->add('ref', TextType::class, [
                self::LABEL => 'Code',
                self::REQUIRED => true,
            ])
            ->add('pole', EntityType::class, [
                self::CSS_CLASS => Pole::class,
                self::CHOICE_LABEL => 'name',
                self::MULTIPLE => false,
                self::GROUP_BY => 'axe.name',
                self::ATTR => [self::CSS_CLASS => 'select2'],
                self::REQUIRED => true,
                self::QUERY_BUILDER => function (EntityRepository $er) {
                    return $er->createQueryBuilder('p')
                        ->leftJoin('p.axe', 'a')
                        ->orderBy('a.name', 'ASC')
                        ->addOrderBy('p.name', 'ASC');
                },
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Thematique::class,
        ]);
    }
}
