<?php

namespace App\Controller\Home;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/")
 */
class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="contact", methods={"GET"})
     * @return Response
     * @IsGranted("ROLE_USER")
     */
    public function contactAction(): Response
    {
        return $this->render('home/contact.html.twig', []);
    }
}
