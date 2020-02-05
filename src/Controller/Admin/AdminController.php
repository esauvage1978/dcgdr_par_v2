<?php

namespace App\Controller\Admin;

use App\Command\CalculTauxCommand;
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
        $this->addFlash('success', $calculTauxCommand->calcul());

        return $this->redirectToRoute('admin_home');
    }

    /**
     * @Route("/command/notificator", name="command_deployement_jalon_notificator", methods={"GET"})
     *
     * @IsGranted("ROLE_GESTIONNAIRE")
     *
     * @return Response
     */
    public function commandDeployementJalonNotificatorAction(DeployementJalonNotificator $deployementJalonNotificator)
    {
        $debut = microtime(true);
        $deployementJalonNotificator->notifyJalonToday();
        $fin = microtime(true);

        $this->addFlash('success', 'Traitement effectué en  '.(int) (($fin - $debut) * 1000).' millisecondes.');

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
        $debut = microtime(true);
        $workflowCommand->calcul();
        dump($workflowCommand->getMessages());
        $fin = microtime(true);

        $affichage = '';
        foreach ($workflowCommand->getMessages() as $message) {
            $affichage = $affichage.'<br/>'.$message;
        }

        $this->addFlash('success', $affichage);

        return $this->redirectToRoute('admin_home');
    }
}
