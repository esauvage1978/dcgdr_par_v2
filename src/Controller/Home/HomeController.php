<?php

namespace App\Controller\Home;

use App\Repository\AxeRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/")
 */
class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home", methods={"GET"})
     * @return Response
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     */
    public function homeAction(AxeRepository $axeRepository): Response
    {
        return $this->render('home/home.html.twig', ['axes'=>$axeRepository->findAllForHome()]);
    }


}
