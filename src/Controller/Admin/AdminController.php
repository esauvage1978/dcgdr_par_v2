<?php

namespace App\Controller\Admin;

use App\Command\CalculTauxCommand;
use App\Command\NotificatorCommand;
use App\Command\WorkflowCommand;
use App\Helper\DeployementJalonNotificator;
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
     * @IsGranted("ROLE_GESTIONNAIRE")
     *
     * @return Response
     */
    public function calculTauxAction(CalculTauxCommand $calculTauxCommand)
    {
        $calculTauxCommand->runTraitement();

        $this->addFlash('info', $calculTauxCommand->getMessagesForAlert());

        return $this->redirectToRoute('admin_home');
    }

    /**
     * @Route("/command/notificator", name="command_jalon_notificator", methods={"GET"})
     *
     * @IsGranted("ROLE_GESTIONNAIRE")
     *
     * @return Response
     */
    public function commandJalonNotificatorAction(NotificatorCommand $notificatorCommand)
    {
        $notificatorCommand->runTraitement();

        $this->addFlash('info', $notificatorCommand->getMessagesForAlert());

        return $this->redirectToRoute('admin_home');
    }

    /**
     * @Route("/command/workflow", name="command_workflow", methods={"GET"})
     *
     * @return Response
     *
     * @IsGranted("ROLE_GESTIONNAIRE")
     */
    public function commandWorkflowAction(WorkflowCommand $workflowCommand)
    {
        $workflowCommand->runTraitement();

        $this->addFlash('info', $workflowCommand->getMessagesForAlert());

        return $this->redirectToRoute('admin_home');
    }
}
