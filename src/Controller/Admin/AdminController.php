<?php

namespace App\Controller\Admin;

use App\Command\CalculTauxCommand;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/", name="admin_home", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function adminHomeAction(): Response
    {
        return $this->render('admin/home.html.twig', []);
    }

    /**
     * @Route("/command/calcultaux", name="command_calcul_taux", methods={"GET"})
     *
     * @return Response
     *
     * @param CalculTauxCommand $calculTauxCommand
     * @IsGranted("ROLE_GESTIONNAIRE")
     */
    public function calculTauxAction(CalculTauxCommand $calculTauxCommand)
    {
        $this->addFlash('success', $calculTauxCommand->calcul());

        return $this->redirectToRoute('admin_home');
    }
}
