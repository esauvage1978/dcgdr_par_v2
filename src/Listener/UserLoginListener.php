<?php

namespace App\Listener;

use App\Entity\User;
use App\Helper\UserSendmail;
use App\Manager\UserManager;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

class UserLoginListener
{
    /**
     * @var UserSendMail
     */
    private $sendmail;

    /**
     * @var UserManager
     */
    private $userManager;

    public function __construct(UserSendMail $sendmail, UserManager $userManager)
    {
        $this->sendmail = $sendmail;
        $this->userManager = $userManager;
    }

    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event): ?int
    {
        /** @var User $user */
        $user = $event->getAuthenticationToken()->getUser();

        $user->setLoginAt(new \DateTime());
        $this->userManager->save($user);


        if (!$user->getEmailValidated()) {
            return $this->sendmail->send($user, $this->sendmail::LOGIN, 'DCGDR PAR : Connexion effectuée');
        }


        return null;
    }
}
