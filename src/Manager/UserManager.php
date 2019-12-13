<?php

namespace App\Manager;

use App\Entity\User;
use App\Validator\UserValidator;
use DateTime;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserManager
{

    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * @var EntityManagerInterface
     */
    private $manager;

    /**
     * @var UserValidator
     */
    private $validator;

    public function __construct(EntityManagerInterface $manager, UserValidator $validator, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->manager = $manager;
        $this->validator = $validator;
        $this->passwordEncoder = $passwordEncoder;
    }

    public function save(User $entity,User $entityClone=null): bool
    {
        $this->initialise($entity, $entityClone);

        if (!$this->validator->isValid($entity)) {
            return false;
        }

        $this->manager->persist($entity);
        $this->manager->flush();

        return true;
    }

    public function initialise(User $user,User $userClone=null)
    {
        $this->encodePassword($user);

        if(!$user->getCreatedAt()) {
            $user->setCreatedAt(new \DateTime());
            $user->setEnable(true);
        }

        if (!$user->getActivateToken()) {
            $user
                ->setActivate(false)
                ->setActivateToken(md5(random_bytes(50)));
        }

        return true;
    }

    public function checkPassword($user, $pwd): bool
    {
        return $this->passwordEncoder->isPasswordValid($user, $pwd);
    }

    public function encodePassword(User $user): string
    {
        $plainPassword = $user->getPlainPassword();
        if ($plainPassword) {
            $user->setPassword(
                $this->passwordEncoder->encodePassword(
                    $user,
                    $plainPassword
                ));
        }

        return true;
    }

    public function getErrors(User $entity)
    {
        return $this->validator->getErrors($entity);
    }

    public function remove(User $entity)
    {
        $this->manager->remove($entity);
        $this->manager->flush();
    }

    public function active(User $user)
    {
        $user->setActivate(true);
        $user->setActivateToken(date_format(new DateTime(), 'Y-m-d H:i:s'));
        $user->setRoles(['ROLE_USER']);

        return $this;
    }

    public function onConnected(User $user): bool
    {
        $user->setLoginAt(new DateTime());

        return true;
    }

    public function initialisePasswordForget(User $user): bool
    {
        $user->setForgetToken(md5(random_bytes(50)));

        return true;
    }

    public function initialisePasswordRecover(User $user, string $plainPassword, string $plainPasswordConfirmm): bool
    {
        $user->setForgetToken(date_format(new DateTime(), 'Y-m-d H:i:s'));
        $user->setPlainPassword($plainPassword);
        $user->setPlainPasswordConfirmation($plainPasswordConfirmm);

        return true;
    }

    public function initialisePasswordChange(User $user, string $plainPassword, string $plainPasswordConfirm): bool
    {
        $user->setPlainPassword($plainPassword);
        $user->setPlainPasswordConfirmation($plainPasswordConfirmm);

        return true;
    }
}
