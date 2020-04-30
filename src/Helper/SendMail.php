<?php

namespace App\Helper;

use App\Entity\User;
use Swift_Mailer;
use Swift_Message;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Twig\Environment;

class SendMail
{
    const DEPLOYEMENT_JALON_TODAY = 'deployementJalonNotificator';
    const ACTION_JALON_TODAY = 'actionJalonNotificator';

    const MailerAction = 'mailer/mailerAction';
    const MailerDeployement = 'mailer/mailerDeployement';

    const USERS_TO = 'user';
    const USERS_FROM = 'user_from';

    /**
     * @var array
     */
    private $usersTo;

    /**
     * @var array
     */
    private $userFrom;

    /**
     * @var Environment
     */
    private $twig;

    /**
     * @var Swift_Mailer
     */
    private $mailer;

    /**
     * Summary of $params.
     *
     * @var ParameterBagInterface
     */
    private $params;

    public function __construct(Environment $twig, Swift_Mailer $mailer, ParameterBagInterface $params)
    {
        $this->twig = $twig;
        $this->mailer = $mailer;
        $this->params = $params;
    }


    public function send(array $datas, string $context, string $objet = null): int
    {
        $this->getUserFrom($datas);

        $this->getUsersTo($datas);

        $message = (new Swift_Message())
            ->setSubject($objet ? $objet : $context)
            ->setFrom($this->userFrom)
            ->setTo($this->usersTo)
            ->setBody(
                $this->twig->render('mail/' . $context . '.html.twig', $datas),
                'text/html'
            );

        return $this->mailer->send($message, $failures);
    }

    private function getUsersTo(array $datas)
    {
        $dataUserTo = $datas[self::USERS_TO];
        if (is_a($dataUserTo, User::class)) {
            $this->usersTo = [$dataUserTo->getEmail() => $dataUserTo->getName()];
        } else {
            $this->usersTo = $dataUserTo;
        }
    }

    private function getUserFrom(array $datas)
    {

        if (array_key_exists(self::USERS_FROM, $datas)) {
            $dataUserFrom = $datas[self::USERS_FROM];
            if (is_a($dataUserFrom, User::class)) {
                $this->userFrom = [$dataUserFrom->getEmail() => $dataUserFrom->getName()];
            } else {
                $this->userFrom = $dataUserFrom;
            }
        } else {
            $this->userFrom = [
                $this->params->get('mailer.mail')
                =>
                    $this->params->get('mailer.name')
            ];
        }
    }
}
