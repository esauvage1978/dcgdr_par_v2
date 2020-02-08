<?php

namespace App\Controller\Dashboard;

use App\Controller\AppControllerAbstract;
use App\Repository\MessageRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AppControllerAbstract
{
    /**
     * @Route("/dashboard", name="dashboard", methods={"GET"})
     * @return Response
     * @IsGranted("ROLE_USER")
     */
    public function dashboardAction(MessageRepository $messageRepository): Response
    {
        return $this->render('dashboard/home.html.twig'
            , ['messages'=> $messageRepository->findBy(['name'=>'dashboard'])]
        );
    }

}
