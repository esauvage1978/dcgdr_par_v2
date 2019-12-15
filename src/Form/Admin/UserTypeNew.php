<?php

namespace App\Form\Admin;

use App\Entity\User;
use App\Form\AppTypeAbstract;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserTypeNew extends AppTypeAbstract
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder = $this->buildFormNameEnableContent($builder);
        $this->buildFormOrganismes($builder);

        $roles = [
            'Utilisateur' => 'ROLE_USER',
            'Gestionnaire local' => 'ROLE_GESTIONNAIRE_LOCAL',
            'Gestionnaire' => 'ROLE_GESTIONNAIRE',
            'Administrateur' => 'ROLE_ADMIN',
        ];

        $builder
            ->add('email', EmailType::class)
            ->add('phone',TelType::class,
                [
                    self::LABEL => 'Téléphone',
                    self::REQUIRED => false,
                ])
            ->add('plainpassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Les deux mots de passe doivent correspondre.',
                'required' => true,
                'first_options' => ['label' => 'Mot de passe'],
                'second_options' => ['label' => 'Tapez le mot de passe à nouveau'],
            ])
            ->add('roles', ChoiceType::class, [
                'choices' => $roles,
                'multiple' => true,
                'expanded' => true,
                'mapped' => true,
                self::LABEL => 'form.roles',
            ])
            ->add('activate', CheckboxType::class,
                [
                    self::LABEL => ' ',
                    self::REQUIRED => false,
                ])
            ->add('loginAt')
            ->add('createdAt')
            ->add('modifiedAt')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
