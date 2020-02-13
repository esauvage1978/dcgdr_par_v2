<?php

namespace App\Form\Mailer;

use App\Entity\User;
use App\Form\AppTypeAbstract;
use App\Repository\ActionRepository;
use App\Repository\CorbeilleRepository;
use App\Repository\DeployementRepository;
use App\Repository\OrganismeRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class MailerFormActionType extends MailerFormType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->buildFormSubjectContent($builder);

        $builder

            ->add('actionvalider', EntityType::class, [
                self::CSS_CLASS => User::class,
                self::CHOICE_LABEL => 'name',
                self::LABEL=>'Valideurs de l\'action',
                self::MULTIPLE => true,
                self::ATTR => [self::CSS_CLASS => 'select2'],
                self::REQUIRED => false,
                self::QUERY_BUILDER => function (EntityRepository $er) use($options)  {
                    return $er->createQueryBuilder(UserRepository::ALIAS_ACTION_VALIDERS)
                        ->select(
                            UserRepository::ALIAS_ACTION_VALIDERS,
                            CorbeilleRepository::ALIAS_ACTION_VALIDERS,
                            ActionRepository::ALIAS
                        )
                        ->leftJoin(UserRepository::ALIAS_ACTION_VALIDERS.'.corbeilles', CorbeilleRepository::ALIAS_ACTION_VALIDERS)
                        ->leftJoin(CorbeilleRepository::ALIAS_ACTION_VALIDERS.'.actionValiders', ActionRepository::ALIAS)
                        ->where(ActionRepository::ALIAS . '.id = :actionid')
                        ->setParameter('actionid', $options['data']['data'])
                        ->orderBy(UserRepository::ALIAS_ACTION_VALIDERS.'.name', 'ASC');
                },
            ])
            ->add('actionwriter', EntityType::class, [
                self::CSS_CLASS => User::class,
                self::CHOICE_LABEL => 'name',
                self::LABEL=>'Pilotes de l\'action',
                self::MULTIPLE => true,
                self::ATTR => [self::CSS_CLASS => 'select2'],
                self::REQUIRED => false,
                self::QUERY_BUILDER => function (EntityRepository $er) use($options)  {
                    return $er->createQueryBuilder(UserRepository::ALIAS_ACTION_WRITERS)
                        ->select(
                            UserRepository::ALIAS_ACTION_WRITERS,
                            CorbeilleRepository::ALIAS_ACTION_WRITERS,
                            ActionRepository::ALIAS
                        )
                        ->leftJoin(UserRepository::ALIAS_ACTION_WRITERS.'.corbeilles', CorbeilleRepository::ALIAS_ACTION_WRITERS)
                        ->leftJoin(CorbeilleRepository::ALIAS_ACTION_WRITERS.'.actionWriters', ActionRepository::ALIAS)
                        ->where(ActionRepository::ALIAS . '.id = :actionid')
                        ->setParameter('actionid', $options['data']['data'])
                        ->orderBy(UserRepository::ALIAS_ACTION_WRITERS.'.name', 'ASC');
                },
            ])
            ->add('deployementwriter', EntityType::class, [
                self::CSS_CLASS => User::class,
                self::CHOICE_LABEL => 'name',
                self::LABEL=>'Pilotes des déploiements',
                self::MULTIPLE => true,
                self::ATTR => [self::CSS_CLASS => 'select2'],
                self::REQUIRED => false,
                self::QUERY_BUILDER => function (EntityRepository $er) use($options)  {
                    return $er->createQueryBuilder(UserRepository::ALIAS_DEPLOYEMENT_WRITERS)
                        ->select(
                            UserRepository::ALIAS_DEPLOYEMENT_WRITERS,
                            CorbeilleRepository::ALIAS_DEPLOYEMENT_WRITERS,
                            DeployementRepository::ALIAS,
                            OrganismeRepository::ALIAS,
                            ActionRepository::ALIAS
                        )
                        ->leftJoin(UserRepository::ALIAS_DEPLOYEMENT_WRITERS.'.corbeilles', CorbeilleRepository::ALIAS_DEPLOYEMENT_WRITERS)
                        ->leftJoin(CorbeilleRepository::ALIAS_DEPLOYEMENT_WRITERS.'.deployementWriters', DeployementRepository::ALIAS)
                        ->leftJoin(DeployementRepository::ALIAS.'.organisme', OrganismeRepository::ALIAS)
                        ->leftJoin(DeployementRepository::ALIAS.'.action', ActionRepository::ALIAS)
                        ->where(ActionRepository::ALIAS . '.id = :actionid')
                        ->setParameter('actionid', $options['data']['data'])
                        ->orderBy(UserRepository::ALIAS_DEPLOYEMENT_WRITERS.'.name', 'ASC');
                },
            ])
            ->add('subject', TextType::class, [
                self::LABEL => ' ',
                self::ATTR => ['placeholder' => 'Objet du mail : '],
                self::REQUIRED => true
            ])
            ->add('content', TextareaType::class, [
                self::LABEL => ' ',
                self::REQUIRED => true,
                self::ATTR => [self::ROWS => 8, self::CSS_CLASS => 'textarea'],
            ]);
    }
}
