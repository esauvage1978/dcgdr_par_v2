<?php

namespace App\Controller\Deployement;

use App\Controller\AppControllerAbstract;
use App\Entity\Action;
use App\Entity\Deployement;
use App\Entity\Organisme;
use App\Form\Deployement\DeployementAppendType;
use App\Form\Deployement\DeployementEditType;
use App\Manager\DeployementManager;
use App\Repository\CorbeilleRepository;
use App\Repository\DeployementRepository;
use App\Repository\OrganismeRepository;
use App\Security\DeployementVoter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DeployementController extends AppControllerAbstract
{
    const ENTITYS = 'deployements';
    const ENTITY = 'deployement';

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
     * @Route("/deploiement/{id}/edit", name="deployement_edit", methods={"GET","POST"})
     *
     * @param string $message =self::MSG_MODIFY
     *
     * @IsGranted("ROLE_USER")
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
     * @Route("/{id}", name="deployement_delete", methods={"DELETE"})
     *
     * @param Deployement        $entity
     * @param DeployementManager $manager
     * @param Request            $request
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
        $this->denyAccessUnlessGranted(DeployementVoter::DELETE, $entity);

        $idAction = $entity->getAction()->getId();

        if ($this->isCsrfTokenValid('delete'.$entity->getId(), $request->request->get('_token'))) {
            $this->addFlash(self::SUCCESS, self::MSG_DELETE);
            $manager->remove($entity);
        } else {
            $this->addFlash(self::DANGER, self::MSG_DELETE_DANGER);
        }

        return $this->redirectToRoute('deployement_liste_edit', ['id' => $idAction]);
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

        $deployement = new Deployement();
        $deployement
            ->setAction($action)
            ->setOrganisme($organisme);

        $deployementManager->save($deployement);

        return $this->redirectToRoute('deployement_edit', ['id' => $deployement->getId()]);
    }

    /**
     * @Route("/deployement/{id}/append", name="deployement_append", methods={"GET"})
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
     * @Route("/deployement/{id}/append/edit", name="deployement_append_edit", methods={"GET","POST"})
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
     * @param Request     $request
     * @param Deployement $entity
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
}
