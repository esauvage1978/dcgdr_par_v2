<?php

namespace App\Controller\Action;

use App\Controller\AppControllerAbstract;
use App\Dto\ActionSearchDto;
use App\Entity\Action;
use App\Form\Action\ActionCreateType;
use App\Form\Action\ActionEditType;
use App\Manager\ActionManager;
use App\Repository\ActionRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class ActionController extends AppControllerAbstract
{
    const ENTITYS = 'actions';
    const ENTITY = 'action';

    /**
     * @Route("/actions/liste", name="action_index", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function indexAction(ActionRepository $repository): Response
    {
        return $this->render(self::ENTITY.'/index.html.twig', [
            self::ENTITYS => $repository->findAllEnableAndNotArchiving(),
        ]);
    }

    /**
     * @Route("/actions/axe/{id_axe}", name="action_for_axe", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function actionsForAxeAction(
        ActionRepository $repository,
        ActionSearchDto $actionSearchDto,
        string $id_axe
): Response
    {
        $actionSearchDto->setAxeId($id_axe);
        return $this->render(self::ENTITY.'/index.html.twig', [
            self::ENTITYS => $repository->findAllForDto($actionSearchDto),
        ]);
    }

    /**
     * @Route("/action/new", name="action_new", methods={"GET","POST"})
     * @IsGranted("ROLE_GESTIONNAIRE")
     */
    public function newAction(Request $request, ActionManager $manager): Response
    {
        $entity = new Action();
        $form = $this->createForm(ActionCreateType::class, $entity);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($manager->save($entity)) {
                $this->addFlash(self::SUCCESS, self::MSG_CREATE);

                return $this->redirectToRoute('action_edit', ['id' => $entity->getId()]);
            }
            $this->addFlash(self::DANGER, self::MSG_ERROR.$manager->getErrors($entity));
        }

        return $this->render('action/new.html.twig', [
            'action' => $entity,
            self::FORM => $form->createView(),
        ]);
    }

    /**
     * @Route("/action/{id}", name="action_show", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function showAction(Action $entity): Response
    {
        return $this->render(self::ENTITY.'/show.html.twig', [
            self::ENTITY => $entity,
        ]);
    }

    /**
     * @Route("/action/{id}/edit", name="action_edit", methods={"GET","POST"})
     * @IsGranted("ROLE_USER")
     */
    public function editAction(
        Request $request,
        Action $entity,
        ActionManager $manager): Response
    {
        $form = $this->createForm(ActionEditType::class, $entity);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($manager->save($entity)) {
                $this->addFlash(self::SUCCESS, self::MSG_MODIFY);
            } else {
                $this->addFlash(self::DANGER, self::MSG_ERROR.$manager->getErrors($entity));
            }
        }

        return $this->render('action/edit.html.twig', [
            'action' => $entity,
            self::FORM => $form->createView(),
        ]);
    }

    /**
     * @Route("/action/{id}", name="action_delete", methods={"DELETE"})
     * @IsGranted("ROLE_GESTIONNAIRE")
     */
    public function deleteAction(Request $request, Action $entity, ActionManager $manager): Response
    {
        return $this->delete($request, $entity, $manager, self::ENTITY);
    }
}
