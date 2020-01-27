<?php

namespace App\Controller\Dashboard;

use App\Controller\AppControllerAbstract;
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
    public function dashboardAction(): Response
    {
        return $this->render('dashboard/home.html.twig'
        );
    }

}
