<?php

namespace App\Controller\Home;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/")
 */
class DocController extends AbstractController
{
    /**
     * @Route("/documentation", name="documentation", methods={"GET"})
     * @return Response
     */
    public function contactAction(): Response
    {

        return $this->render('home/doc.html.twig', []);
    }


}
