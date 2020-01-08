<?php

namespace App\Controller\Indicator;

use App\Controller\AppControllerAbstract;
use App\Entity\Action;
use App\Entity\Indicator;
use App\Form\Indicator\IndicatorType;
use App\Repository\IndicatorRepository;
use App\Manager\IndicatorManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/indicator")
 */
class IndicatorController extends AppControllerAbstract
{
    const ENTITYS = 'indicators';
    const ENTITY = 'indicator';

    /**
     * @Route("/{id}/new", name="indicator_new", methods={"GET","POST"})
     *
     * @param Request          $request
     * @param IndicatorManager $manager
     *
     * @return Response
     *
     * @IsGranted("ROLE_USER")
     */
    public function newAction(Action $action, Request $request, IndicatorManager $manager): Response
    {
        $entity = new Indicator();
        $entity->setAction($action);
        $form = $this->createForm(IndicatorType::class, $entity);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->save($entity);
            $this->addFlash(self::SUCCESS, self::MSG_CREATE);
            return $this->redirectToRoute('indicator_edit', ['id' => $entity->getId()]);
        }

        return $this->render('indicator/new.html.twig', [
            self::ENTITY => $entity,
            'action' => $action,
            self::FORM => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="indicator_edit", methods={"GET","POST"})
     *
     * @param Indicator        $entity
     * @param IndicatorManager $manager
     * @param string           $message =self::MSG_MODIFY
     * @param Request          $request
     *
     * @return Response
     *
     * @IsGranted("ROLE_USER")
     */
    public function editAction(Request $request, Indicator $entity, IndicatorManager $manager, string $message = self::MSG_MODIFY): Response
    {
        $form = $this->createForm(IndicatorType::class, $entity);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->save($entity);
                $this->addFlash(self::SUCCESS, $message);

        }

        return $this->render('indicator/edit.html.twig', [
            self::ENTITY => $entity,
            self::FORM => $form->createView(),
        ]);
    }


    /**
     * @Route("/{id}", name="indicator_delete", methods={"DELETE"})
     *
     * @param Indicator        $entity
     * @param IndicatorManager $manager
     * @param Request          $request
     *
     * @return Response
     *
     * @IsGranted("ROLE_GESTIONNAIRE")
     */
    public function deleteAction(Request $request, Indicator $entity, IndicatorManager $manager): Response
    {
        $idAction = $entity->getAction()->getId();

        if ($this->isCsrfTokenValid('delete'.$entity->getId(), $request->request->get('_token'))) {
            $this->addFlash(self::SUCCESS, self::MSG_DELETE);
            $manager->remove($entity);
        } else {
            $this->addFlash(self::DANGER, self::MSG_DELETE_DANGER);
        }

        return $this->redirectToRoute('action_edit', ['id' => $idAction]);
    }
}
