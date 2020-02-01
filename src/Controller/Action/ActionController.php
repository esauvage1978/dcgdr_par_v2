<?php

namespace App\Controller\Action;

use App\Controller\AppControllerAbstract;
use App\Dto\ActionSearchDto;
use App\Entity\Action;
use App\Form\Action\ActionCreateType;
use App\Form\Action\ActionEditType;
use App\Helper\ActionFilter;
use App\Manager\ActionManager;
use App\Repository\ActionFileRepository;
use App\Repository\ActionRepository;
use App\Repository\CadrageFileRepository;
use App\Security\ActionVoter;
use App\Workflow\WorkflowData;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\File\File;
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
    public function indexAction(
        ActionRepository $repository,
        ActionSearchDto $actionSearchDto
    ): Response {
        return $this->render(self::ENTITY.'/index.html.twig', [
            self::ENTITYS => $repository->findAllForDto($actionSearchDto, ActionRepository::FILTRE_DTO_INIT_TABLEAU),
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
    ): Response {
        $actionSearchDto->setAxeId($id_axe);

        return $this->render(self::ENTITY.'/index.html.twig', [
            self::ENTITYS => $repository->findAllForDto($actionSearchDto, ActionRepository::FILTRE_DTO_INIT_TABLEAU),
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
    public function showAction(
        ActionSearchDto $actionSearchDto,
        ActionRepository $actionRepository,
        string $id
    ): Response {
        $actionSearchDto->setId($id);
        /** @var Action $action */
        $action = $actionRepository->findAllForDto($actionSearchDto, ActionRepository::FILTRE_DTO_INIT_UNITAIRE)[0];

        $this->denyAccessUnlessGranted(ActionVoter::READ, $action);

        return $this->render(self::ENTITY.'/show.html.twig', [
            self::ENTITY => $action,
        ]);
    }

    /**
     * @Route("/action/{id}/file/{fileId}", name="action_file_show", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function actionFileShowAction(
        Request $request,
        Action $action,
        string $fileId,
        ActionFileRepository $actionFileRepository): Response
    {
        $this->denyAccessUnlessGranted(ActionVoter::READ, $action);

        $actionFile = $actionFileRepository->find($fileId);

        // load the file from the filesystem
        $file = new File($actionFile->getHref());

        // rename the downloaded file
        return $this->file($file, $actionFile->getTitle().'.'.$actionFile->getFileExtension());
    }

    /**
     * @Route("/action/{id}/filecadrage/{fileId}", name="cadrage_file_show", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function cadrageFileShowAction(
        Request $request,
        Action $action,
        string $fileId,
        CadrageFileRepository $cadrageFileRepository): Response
    {
        $this->denyAccessUnlessGranted(ActionVoter::READ, $action);

        $cadrageFile = $cadrageFileRepository->find($fileId);

        // load the file from the filesystem
        $file = new File($cadrageFile->getHref());

        // rename the downloaded file
        return $this->file($file, $cadrageFile->getTitle().'.'.$cadrageFile->getFileExtension());
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
        $this->denyAccessUnlessGranted(ActionVoter::UPDATE, $entity);

        $form = $this->createForm(ActionEditType::class, $entity);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->save($entity);
            $this->addFlash(self::SUCCESS, self::MSG_MODIFY);

            $this->redirectToRoute('action_edit', ['id' => $entity->getId()]);
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

    /**
     * @Route("/workflow/actions/{state?}", name="actions_by_state", methods={"GET"})
     *
     * @return Response
     *
     * @IsGranted("ROLE_USER")
     */
    public function actionsByStateAction(
        ActionRepository $actionRepository,
        string $state,
        ActionSearchDto $actionSearchDto
    ): Response {
        $actionSearchDto->setState($state);

        $complement = '';
        $nextSteps = WorkflowData::getTransitionsForState($state);
        $resultRepo = $actionRepository->findAllForDto($actionSearchDto, ActionRepository::FILTRE_DTO_INIT_TABLEAU);

        return $this->render('action/index_dashboard.html.twig', [
            'actions' => $resultRepo,
            'complement' => $complement,
            'nextSteps' => $nextSteps,
        ]);
    }

    /**
     * @Route("/my/action/{filter?}", name="my_action", methods={"GET"})
     *
     * @return Response
     *
     * @IsGranted("ROLE_USER")
     */
    public function myActionAction(
        ActionFilter $actionFilter,
        ?string $filter): Response
    {
        return $this->render('action/indexmy.html.twig',
            $actionFilter->getData($filter)
        );
    }
}
