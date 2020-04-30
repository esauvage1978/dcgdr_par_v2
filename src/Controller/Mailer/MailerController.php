<?php

namespace App\Controller\Mailer;

use App\Controller\AppControllerAbstract;
use App\Entity\Action;
use App\Entity\Deployement;
use App\Entity\Mailer;
use App\Form\Mailer\MailerFormActionType;
use App\Form\Mailer\MailerFormDeployementType;
use App\Helper\SendMail;
use App\Manager\MailerManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class MailerController extends AppControllerAbstract
{
    /**
     * @Route("/mailer/composer/{id}", name="mailer_action_composer")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function mailerActionComposer(
        Request $request,
        Action $action,
        MailerManager $manager,
        SendMail $sendMail
    )
    {
        $form = $this->createForm(MailerFormActionType::class, ['data' => $action->getId()]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $mailer = $manager->initialiseMailer($form->getData());
            if (is_a($mailer, Mailer::class)) {
                $sendMail->send(
                    [
                        SendMail::USERS_FROM => [$this->getUser()->getEmail() => $this->getUser()->getName()],
                        SendMail::USERS_TO => $manager->getUsersEmailTo(),
                        'action' => $action,
                        'content' =>'<p> de <b>' .$mailer->getUserFrom() .'</b></p><p>' .$mailer->getContent() . '</p>'
                    ],
                    SendMail::MailerAction,
                    'DCGDR PAR - ' . $mailer->getSubject()
                );

                $mailer->setAction($action);

                $manager->save($mailer);

                $this->addFlash(self::SUCCESS, 'Message envoyé');
            } else {
                $this->addFlash(self::DANGER, 'Une erreur est survenue. Le mail n\'est pas envoyé. La cause probable est une absence de destinataire');
            }
        }
        return $this->render('mailer/composerAction.html.twig', [
            'controller_name' => 'MailerController',
            'action' => $action,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/mailer/{id}", name="mailer_action_history")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function mailierActionHistory(
        Action $action
    )
    {
        return $this->render('mailer/history_action.html.twig', [
            'action' => $action,
        ]);
    }

    /**
     * @Route("/mailer/composer/deployement/{id}", name="mailer_deployement_composer")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function mailerDeployementComposer(
        Request $request,
        Deployement $deployement,
        MailerManager $manager,
        SendMail $sendMail
    )
    {
        $form = $this->createForm(MailerFormDeployementType::class, ['data' => $deployement->getId()]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $mailer = $manager->initialiseMailer($form->getData());
            if (is_a($mailer, Mailer::class)) {
                $sendMail->send(
                    [
                        SendMail::USERS_FROM => [$this->getUser()->getEmail() => $this->getUser()->getName()],
                        SendMail::USERS_TO => $manager->getUsersEmailTo(),
                        'deployement' => $deployement,
                        'content' =>'<p> de <b>' .$mailer->getUserFrom() .'</b></p><p>' .$mailer->getContent() . '</p>'
                    ],
                    SendMail::MailerDeployement,
                    'DCGDR PAR - ' . $mailer->getSubject()
                );

                $mailer->setDeployement($deployement);

                $manager->save($mailer);

                $this->addFlash(self::SUCCESS, 'Message envoyé');
            } else {
                $this->addFlash(self::DANGER, 'Une erreur est survenue. Le mail n\'est pas envoyé. La cause probable est une absence de destinataire');
            }
        }
        return $this->render('mailer/composerDeployement.html.twig', [
            'controller_name' => 'MailerController',
            'deployement' => $deployement,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/mailer/deployement/{id}", name="mailer_deployement_history")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function mailierDeployementHistory(
        Deployement $deployement
    )
    {
        return $this->render('mailer/history_deployement.html.twig', [
            'deployement' => $deployement,
        ]);
    }
}
