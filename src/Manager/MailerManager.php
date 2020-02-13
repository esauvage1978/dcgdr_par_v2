<?php

namespace App\Manager;

use App\Entity\EntityInterface;
use App\Entity\Mailer;
use App\Entity\User;
use App\Helper\SendMail;
use App\Validator\MailerValidator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class MailerManager extends ManagerAbstract
{
    /**
     * @var array
     */
    private $usersTo;

    /**
     * @var array
     */
    private $emailsTo;

    /**
     * @var Security
     */
    private $securityContext;


    public function __construct(
        EntityManagerInterface $manager,
        MailerValidator $validator,
        Security $securityContext
    ) {
        $this->usersTo = [];
        $this->emailsTo = [];
        $this->securityContext = $securityContext;

        parent::__construct($manager, $validator);

    }

    public function initialise(EntityInterface $entity): void
    {
    }

    public function checkMailer($data): bool
    {
        if (
            empty($data)
            or empty($this->usersTo)
            or empty($data['subject'])
            or empty($data['content'])
        ) {
            return false;
        }

        return true;
    }

    public function initialiseMailer($data): ?Mailer
    {
        if(array_key_exists('actionvalider',$data)) {
            $this->setUsers($data['actionvalider']);
        }
        if(array_key_exists('actionwriter',$data)) {
        $this->setUsers($data['actionwriter']);
        }
            if(array_key_exists('deployementwriter',$data)) {
        $this->setUsers($data['deployementwriter']);
            }

        if (!$this->checkMailer($data)) {
            return null;
        }

        /** @var User $userFrom */
        $userFrom = $this->securityContext->getToken()->getUser();

        $mailer = new Mailer();

        $mailer
            ->setUserFrom($userFrom->getName() . ' [ '. $userFrom->getEmail() . ' ]')
            ->setUsersTo(implode(' ; ', $this->usersTo))
            ->setSubject($data['subject'])
            ->setContent($data['content']);

        return $mailer;
    }

    private function setUsers($data)
    {
        foreach ($data as $user) {
            $name = $user->getName() . ' [ '. $user->getEmail() . ' ]';
            $userMail=[ $user->getEmail()=>$user->getName()];
            $this->usersTo = array_merge($this->usersTo, [$name]);
            $this->emailsTo = array_merge($this->emailsTo, $userMail);
        }
    }

    public function getUsersEmailTo()
    {
        return $this->emailsTo;
    }

}
