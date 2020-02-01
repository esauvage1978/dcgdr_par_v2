<?php

namespace App\Controller\Workflow;

use App\Controller\AppControllerAbstract;
use App\Entity\Action;
use App\Repository\ActionStateRepository;
use App\Workflow\WorkflowActionManager;
use DateTime;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/workflow")
 */
class ActionController extends AppControllerAbstract
{
    const ENTITYS = 'actions';
    const ENTITY = 'action';

    /**
     * @Route("/{id}/check", name="workflow_action_check", methods={"GET","POST"})
     *
     * @param Action          $action
     * @param WorkflowActionManager $workflow
     *
     * @return Response
     *
     * @IsGranted("ROLE_USER")
     */
    public function checkAction(Action $action, WorkflowActionManager $workflow): Response
    {
        return $this->render('verif/workflow.html.twig', [
            'action' => $action,
        ]);
    }

    /**
     * @Route("/{id}/check/{transition}", name="workflow_action_check_apply_transition", methods={"GET","POST"})
     *
     * @param Action          $action
     * @param WorkflowActionManager $workflow
     *
     * @return Response
     *
     * @IsGranted("ROLE_USER")
     */
    public function checkApplyTransitionAcion(Action $action, WorkflowActionManager $workflow, string $transition): Response
    {
        $action->setContentState('Modification avec la transition : ' . $transition);

        $workflow->applyTransition($action, $transition,'Modification effectuée par l\'administrateur');

        return $this->redirectToRoute('workflow_action_check',['id'=>$action->getId()]);
    }
    /**
     * @Route("/{id}/history", name="workflow_action_history", methods={"GET"})
     *
     * @param ActionStateRepository $repository
     * @param Action $action
     *
     * @return Response
     *
     * @IsGranted("ROLE_USER")
     */
    public function showHistoryAction(ActionStateRepository $repository, Action $action): Response
    {
        return $this->render('workflow/history.html.twig', [
            self::ENTITYS => $repository->findAllForAction($action->getId()),
            self::ENTITY => $action,
        ]);
    }

    /**
     * @Route("/{id}/{transition}", name="workflow_action_apply_transition", methods={"GET","POST"})
     *
     * @param Request $request
     * @param Action $entity
     * @param WorkflowActionManager $workflowActionManager
     * @param string $transition
     *
     * @return Response
     *
     * @IsGranted("ROLE_USER")
     * @throws \Exception
     */
    public function applyTransitionAction(Request $request, Action $entity, WorkflowActionManager $workflowActionManager, string $transition): Response
    {
        if ($this->isCsrfTokenValid($transition . $entity->getId(), $request->request->get('_token'))) {

            $content=$request->request->get($transition . '_content');

            $result = $workflowActionManager->applyTransition($entity, $transition,$content);

            if ($result) {
                $this->addFlash(self::SUCCESS, 'Le changement d\'état est effectué');

                return $this->redirectToRoute('action_show', ['id' => $entity->getId()]);
            }
            $this->addFlash(self::DANGER, 'Le changement d\'état n\'a pas abouti. Les conditions ne sont pas remplies.');
        }

        return $this->redirectToRoute('action_edit', ['id' => $entity->getId()]);
    }



}
