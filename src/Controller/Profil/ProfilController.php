<?php

namespace App\Controller\Profil;

use App\Controller\AppControllerAbstract;
use App\Entity\User;
use App\Form\Profil\ProfilType;
use App\Form\ProfileType;
use App\Manager\UserManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/profil")
 * @IsGranted("ROLE_USER")
 */
class ProfilController extends AppControllerAbstract
{
    CONST ENTITY='user';

    /**
     * @Route("/", name="profil_home")
     *
     * @var Request $request
     * @var UserManager $userManager
     *
     * @return Response
     *
     */
    public function profileHomeAction(Request $request,  UserManager $manager): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $oldUserMail=(clone $user)->getEmail();
        $form = $this->createForm(ProfilType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($manager->save($user,$oldUserMail)) {
                $this->addFlash(self::SUCCESS, self::MSG_MODIFY);
            } else {
                $this->addFlash(self::DANGER, self::MSG_ERROR . $manager->getErrors($user));
            }
        }

        return $this->render('profil/home.html.twig', [
            self::ENTITY => $user,
            'form' => $form->createView()
        ]);
    }


}
