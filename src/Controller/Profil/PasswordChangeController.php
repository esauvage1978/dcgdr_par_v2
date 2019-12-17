<?php

/*
 *
 *  @author ragus.
 *
 * (c) Ragus <emmanuel.sauvage@live.fr>
 *
 */

namespace App\Controller\Profil;

use App\Controller\AppControllerAbstract;
use App\Form\Profil\PasswordChangeFormType;
use App\Manager\UserManager;
use App\Repository\UserRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/profil")
 * @IsGranted("ROLE_USER")
 */
class PasswordChangeController extends AppControllerAbstract
{
    const DOMAINE = 'profil';

    /**
     * @Route("/password-change", name="profil_password_change")
     */
    public function changePasswordAction(
        Request $request,
        UserRepository $userRepository,
        UserManager $userManager,
        UserPasswordEncoderInterface $encoder): Response
    {
        $user = $this->getUser();

        $form = $this->createForm(PasswordChangeFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($userManager->checkPassword($user, $form->getData()['plainPasswordOld'])) {
                $userManager->initialisePasswordChange(
                    $user,
                    $form->getData()['plainPassword'],
                    $form->getData()['plainPasswordConfirmation']
                );

                $userManager->encodePassword($user);

                $userManager->save($user);

                $this->addFlash(self::SUCCESS, self::MSG_MODIFY);

                return $this->redirectToRoute('profil_home');
            }

            $this->addFlash(self::DANGER, 'L\'ancien mot de passe est incorrect.');
        }

        return $this->render(self::DOMAINE.'/passwordChange.html.twig', [self::FORM => $form->createView()]);
    }
}
