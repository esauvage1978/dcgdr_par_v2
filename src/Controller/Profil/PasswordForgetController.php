<?php

namespace App\Controller\Profil;

use App\Controller\AppControllerAbstract;
use App\Event\UserPasswordForgetEvent;
use App\Form\Profil\PasswordForgetFormType;
use App\Form\Profil\PasswordRecoverFormType;
use App\Manager\UserManager;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

/**
 * @Route("/profil")
 */
class PasswordForgetController extends AppControllerAbstract
{
    /**
     * @Route("/password-forget", name="profil_password_forget")
     */
    public function forgetAction(
        Request $request,
        UserRepository $userRepository,
        UserManager $userManager,
        EventDispatcherInterface $dispatcher): Response
    {
        $form = $this->createForm(PasswordForgetFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $userRepository->findOneBy(['email' => $form->getData()['email']]);

            if (null !== $user) {
                $this->addFlash(self::SUCCESS, 'Le mail de récupération du mot de passe est envoyé !');

                $userManager->initialisePasswordForget($user);
                $userManager->save($user);

                $event = new UserPasswordForgetEvent($user);
                $dispatcher->dispatch($event, UserPasswordForgetEvent::NAME);

                return $this->redirectToRoute('user_login');
            }
            $this->addFlash(self::DANGER, 'L\'utilisateur n\'a pas été trouvé.');
        }

        return $this->render('profil/passwordForget.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/password-recover/{token}", name="profil_password_recover")
     *
     * @param Request $request
     * @param string $token
     * @param UserRepository $userRepository
     * @param UserManager $userManager
     * @return Response
     */
    public function recoverAction(
        Request $request,
        string $token,
        UserRepository $userRepository,
        UserManager $userManager): Response
    {
        $form = $this->createForm(PasswordRecoverFormType::class);
        $form->handleRequest($request);
        $user = $userRepository->findOneBy(['forget_token'=>$token]);

        if (!$user) {
            $this->addFlash(self::WARNING, 'L\'adresse de récupération du mot de passe est incorrecte !');

            return $this->redirectToRoute('home');
        }
        if ($form->isSubmitted() && $form->isValid()) {
            $userManager->initialisePasswordRecover($user,
                $form->getData()['plainPassword'],
                $form->getData()['plainPasswordConfirmation']);

            if ($userManager->save($user)) {
                $this->addFlash(self::SUCCESS, 'Votre mot de passe est changé. Vous pouvez vous connecter !');

                return $this->redirectToRoute('user_login');
            }
        }

        return $this->render('profil/passwordRecover.html.twig', [
            self::FORM => $form->createView()
        ]);
    }

}
