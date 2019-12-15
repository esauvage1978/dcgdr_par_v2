<?php

namespace App\Form\Admin;

use App\Entity\Corbeille;
use App\Entity\Organisme;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;

class CorbeilleGestLocalType extends CorbeilleType
{
    /**
     * @var Security
     */
    private $securityContext;

    public function __construct(Security $securityContext)
    {
        $this->securityContext = $securityContext;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder = $this->buildFormGenerique($builder);

        $user = $this->securityContext->getToken()->getUser();
        if (!$user) {
            throw new \LogicException(
                'Le formulaire ne peut pas être utilisé sans utilisateur connecté!'
            );
        }

        $id = $user->getId();

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) use ($id) {
                $form = $event->getForm();

                $formOptions = [
                    'class' => Organisme::class,
                    self::CHOICE_LABEL => 'fullname',
                    self::REQUIRED => true,
                    self::QUERY_BUILDER => function (EntityRepository $er) use ($id) {
                        return $er->createQueryBuilder('o')
                            ->innerJoin('o.users', 'u')
                            ->andWhere('u.id = :val')
                            ->setParameter('val', $id);
                    },
                ];
                $form->add('organisme', EntityType::class, $formOptions);
            }
        );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Corbeille::class,
        ]);
    }
}
