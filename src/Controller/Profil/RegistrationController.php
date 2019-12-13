<?php

namespace App\Controller\Profil;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/")
 */
class RegistrationController extends AbstractController
{
    /**
     * @Route("/registration", name="profil_registration")
     * @return Response
     */
    public function registrerAction(): Response
    {
        return null;
    }
}
