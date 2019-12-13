<?php

namespace App\Controller\Profil;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
/**
 * @Route("/profil")
 */
class PasswordForgetController extends AbstractController
{
    /**
     * @Route("/password-forget", name="profil_password_forget")
     */
    public function forgetAction(): Response
    {
        return null;
    }

    /**
     * @Route("/password-recover/{token}", name="profil_password_recover")
     * @return Response
     */
    public function recoverAction(): Response
    {
        return null;
    }
}
