<?php

namespace App\Manager;

use App\Entity\User;
use App\Helper\ToolCollecion;
use App\Repository\CorbeilleRepository;
use App\Repository\OrganismeRepository;
use App\Validator\UserValidator;
use DateTime;
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

    /**
     * @var OrganismeRepository
     */
    private $organismeRepository;

    /**
     * @var CorbeilleRepository
     */
    private $corbeilleRepository;

    public function __construct(
        EntityManagerInterface $manager,
        UserValidator $validator,
        UserPasswordEncoderInterface $passwordEncoder,
        OrganismeRepository $organismeRepository,
    CorbeilleRepository $corbeilleRepository
    ) {
        $this->manager = $manager;
        $this->validator = $validator;
        $this->passwordEncoder = $passwordEncoder;
        $this->organismeRepository = $organismeRepository;
        $this->corbeilleRepository = $corbeilleRepository;
    }

    public function save(User $user): bool
    {
        $this->initialise($user);

        if (!$this->validator->isValid($user)) {
            return false;
        }

        $this->manager->persist($user);
        $this->manager->flush();

        return true;
    }

    public function initialise(User $user)
    {
        $this->encodePassword($user);

        if (null === $user->getCreatedAt()) {
            $user->setCreatedAt(new \DateTime());
            $user->setEnable(true);
        } else {
            $user->setModifiedAt(new \DateTime());
        }

        if (!$user->getEmailValidatedToken()) {
            $user
                ->setEmailValidated(false)
                ->setEmailValidatedToken(md5(random_bytes(50)));
        }

        if (!empty($user->getId())) {
            $this->setRelation(
                $user,
                $this->organismeRepository->findAllForUser($user->getId()),
                $user->getOrganismes()
            );
            $this->setRelation(
                $user,
                $this->corbeilleRepository->findAllForUser($user->getId()),
                $user->getCorbeilles()
            );
        }

        return true;
    }

    public function setRelation(User $user, $entitysOld, $entitysNew)
    {
        $em = new ToolCollecion($entitysOld, $entitysNew->toArray());

        foreach ($em->getDeleteDiff() as $entity) {
            $entity->removeUser($user);
        }

        foreach ($em->getInsertDiff() as $entity) {
            $entity->addUser($user);
        }
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

    public function validateEmail(User $user)
    {
        $user->setEmailValidated(true);
        $user->setEmailValidatedToken(date_format(new DateTime(), 'Y-m-d H:i:s'));
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
        $user->setPlainPasswordConfirmation($plainPasswordConfirm);

        return true;
    }
}
