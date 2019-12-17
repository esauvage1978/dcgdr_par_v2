<?php

namespace App\Manager;

use App\Entity\Avatar;
use App\Entity\User;
use App\Helper\ToolCollecion;
use App\Repository\CorbeilleRepository;
use App\Repository\OrganismeRepository;
use App\Repository\UserRepository;
use App\Validator\UserValidator;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserManager
{
    private $avatar= 'data:image/png;base64,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';


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

    /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(
        EntityManagerInterface $manager,
        UserValidator $validator,
        UserPasswordEncoderInterface $passwordEncoder,
        OrganismeRepository $organismeRepository,
    CorbeilleRepository $corbeilleRepository,
    UserRepository $userRepository
    ) {
        $this->manager = $manager;
        $this->validator = $validator;
        $this->passwordEncoder = $passwordEncoder;
        $this->organismeRepository = $organismeRepository;
        $this->corbeilleRepository = $corbeilleRepository;
        $this->userRepository = $userRepository;
    }

    public function save(User $user,$oldUserMail=null): bool
    {
        $this->initialise($user,$oldUserMail);

        if (!$this->validator->isValid($user)) {
            return false;
        }

        $this->manager->persist($user);
        $this->manager->flush();

        return true;
    }

    public function initialise(User $user,$oldUserMail=null)
    {
        $this->encodePassword($user);

        if (null === $user->getCreatedAt()) {
            $user->setCreatedAt(new \DateTime());
            $user->setEnable(true);
        } else {
            $user->setModifiedAt(new \DateTime());
        }


        if (!$user->getEmailValidatedToken() or
            ($user->getEmail() !== $oldUserMail and null !== $oldUserMail)) {
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

        if (!$user->getAvatar()) {
            $this->avatarAdd($user, $this->avatar);
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

    public function avatarAdd(User $user, string $image): bool
    {
        $UserAvatar =
            !$user->getAvatar()
                ?
                new Avatar()
                :
                $user->getAvatar();

        $UserAvatar->setImage($image);
        $user->setAvatar($UserAvatar);

        return true;
    }
}
