<?php

namespace App\Controller\Deployement;

use App\Controller\AppControllerAbstract;
use App\Entity\Action;
use App\Entity\Deployement;
use App\Entity\Organisme;
use App\Form\Deployement\DeployementAppendType;
use App\Form\Deployement\DeployementEditType;
use App\Helper\DeployementFilter;
use App\Manager\DeployementManager;
use App\Repository\CorbeilleRepository;
use App\Repository\DeployementFileRepository;
use App\Repository\DeployementRepository;
use App\Repository\OrganismeRepository;
use App\Security\DeployementVoter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DeployementController extends AppControllerAbstract
{
    const ENTITYS = 'deployements';
    const ENTITY = 'deployement';

    /**
     * @Route("/deployement/{id}/file/{fileId}", name="deployement_file_show", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function deployementFileShowAction(
        Request $request,
        Deployement $deployement,
        string $fileId,
        DeployementFileRepository $deployementFileRepository): Response
    {
        $this->denyAccessUnlessGranted(DeployementVoter::READ, $deployement);

        $deployementFile = $deployementFileRepository->find($fileId);

        // load the file from the filesystem
        $file = new File($deployementFile->getHref());

        // rename the downloaded file
        return $this->file($file, $deployementFile->getTitle().'.'.$deployementFile->getFileExtension());
    }

    /**
     * @Route("/action/{id}/deployements", name="deployements_for_action", methods={"GET"})
     *
     * @IsGranted("ROLE_USER")
     */
    public function indexAction(Action $action, DeployementRepository $repository, OrganismeRepository $organismeRepository): Response
    {
        return $this->render(self::ENTITY.'/index.html.twig', [
            self::ENTITYS => $repository->findAllForAction($action->getId()),
            'organismes' => $organismeRepository->findAll(),
            'action' => $action,
        ]);
    }

    /**
     * @Route("/deploiement/{id}/actionedit", name="deployement_edit", methods={"GET","POST"})
     *
     * @param string $message =self::MSG_MODIFY
     *
     * @IsGranted("ROLE_USER")
     *
     * @return Response
     */
    public function editAction(
        Request $request,
        Deployement $entity,
        DeployementManager $manager,
        string $message = self::MSG_MODIFY): Response
    {
        $form = $this->createForm(DeployementEditType::class, $entity);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->save($entity);
            $this->addFlash(self::SUCCESS, $message);
        }

        return $this->render('deployement/edit.html.twig', [
            self::ENTITY => $entity,
            self::FORM => $form->createView(),
        ]);
    }

    /**
     * @Route("/deployement/{id}", name="deployement_delete", methods={"DELETE"})
     *
     * @return Response
     *
     * @IsGranted("ROLE_GESTIONNAIRE")
     */
    public function deleteAction(
        Request $request,
        Deployement $entity,
        DeployementManager $manager): Response
    {

        $idAction = $entity->getAction()->getId();

        if ($this->isCsrfTokenValid('delete'.$entity->getId(), $request->request->get('_token'))) {
            $this->addFlash(self::SUCCESS, self::MSG_DELETE);
            $manager->remove($entity);
        } else {
            $this->addFlash(self::DANGER, self::MSG_DELETE_DANGER);
        }

        return $this->redirectToRoute('deployements_for_action', ['id' => $idAction]);
    }

    /**
     * @Route("/action/{id}/deployements/new/{organismeid}", name="deployement_new", methods={"GET"})
     *
     * @IsGranted("ROLE_USER")
     */
    public function addDeployementAction(
        Action $action,
        string $organismeid,
        OrganismeRepository $organismeRepository,
        CorbeilleRepository $corbeilleRepository,
        DeployementManager $deployementManager): Response
    {
        /** @var Organisme $organisme */
        $organisme = $organismeRepository->findOneBy(['id' => $organismeid]);

        if (!is_a($organisme, Organisme::class)) {
            $this->addFlash(self::DANGER, 'L\'organisme '.$organismeid.' n\'a pas été trouvé : ');

            return $this->redirectToRoute('deployement_liste_edit', ['id' => $action->getId()]);
        }

        $corbeilles = $corbeilleRepository->findBy(['organisme' => $organisme, 'showDefault' => true]);

        $deployement = new Deployement();
        $deployement
            ->setAction($action)
            ->setOrganisme($organisme);

        foreach ($corbeilles as $corbeille) {
            $deployement->addWriter($corbeille);
        }

        $deployementManager->save($deployement);

        return $this->redirectToRoute('deployement_edit', ['id' => $deployement->getId()]);
    }

    /**
     * @Route("/deployement/{id}", name="deployement_append", methods={"GET"})
     *
     * @IsGranted("ROLE_USER")
     */
    public function appendShowAction(Request $request, Deployement $entity): Response
    {
        $this->denyAccessUnlessGranted(DeployementVoter::APPEND_READ, $entity);

        return $this->render(self::ENTITY.'/append.html.twig', [
            self::ENTITY => $entity,
        ]);
    }

    /**
     * @Route("/deployement/{id}/edit", name="deployement_append_edit", methods={"GET","POST"})
     *
     * @IsGranted("ROLE_USER")
     */
    public function appendEditAction(Request $request, Deployement $entity, DeployementManager $manager): Response
    {
        $this->denyAccessUnlessGranted(DeployementVoter::APPEND_UPDATE, $entity);

        $form = $this->createForm(DeployementAppendType::class, $entity);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->save($entity);
            $this->addFlash(self::SUCCESS, self::MSG_MODIFY);
        }

        return $this->render(self::ENTITY.'/append_edit.html.twig', [
            self::ENTITY => $entity,
            self::FORM => $form->createView(),
            'action' => $entity->getAction(),
        ]);
    }

    /**
     * @Route("/{id}/history", name="deployement_history", methods={"GET"})
     *
     * @return Response
     *
     * @IsGranted("ROLE_USER")
     */
    public function historyShowAction(Request $request, Deployement $entity): Response
    {
        return $this->render(self::ENTITY.'/history.html.twig', [
            self::ENTITY => $entity,
        ]);
    }

    /**
     * @Route("/my/deployement/{filter?}", name="my_deployement", methods={"GET"})
     *
     * @return Response
     *
     * @IsGranted("ROLE_USER")
     */
    public function myDeployementAction(
        DeployementFilter $deploiementFilter,
        ?string $filter): Response
    {
        return $this->render('deployement/indexmy.html.twig',
            $deploiementFilter->getData($filter)
            );
    }

    /**
     * @Route("/deployement/organisme/{filter?}", name="deployements_for_organisme", methods={"GET"})
     *
     * @return Response
     *
     * @IsGranted("ROLE_USER")
     */
    public function deployementForOrganismeAction(
        OrganismeRepository $repo,
        DeployementFilter $deploiementFilter,
        string $filter
): Response
    {
        /** @var Organisme $organisme */
        $organisme=$repo->find($filter);
        return $this->render('deployement/index_organisme.html.twig',
                array_merge(['organisme'=>$organisme],
                $deploiementFilter->getData('organisme_' . $organisme->getId()))
        );
    }


}
