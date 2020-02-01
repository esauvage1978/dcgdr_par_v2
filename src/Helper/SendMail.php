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

        if (is_a($datas['user'], User::class)) {
            $mail = [[$datas['user']->getEmail() => $datas['user']->getUsername()]];
        } else {
            $mail=$datas['user'];
        }

        $message = (new Swift_Message())
            ->setSubject($objet ? $objet : $context)
            ->setFrom([
                $this->params->get('mailer.mail') => $this->params->get('mailer.name'), ])
            ->setTo($mail)
            ->setBody(
                $this->twig->render('mail/'.$context.'.html.twig', $datas),
                'text/html'
            );

        return $this->mailer->send($message, $failures);
    }
}
