<?php

namespace App\Controller;

use App\Entity\EntityInterface;
use App\Entity\IEntity;
use App\Manager\IManager;
use App\Manager\ManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

abstract class AppControllerAbstract extends AbstractController
{
    const SUCCESS='success';
    const DANGER='danger';
    const INFO='info';
    const WARNING='warning';

    CONST MSG_CREATE='La création est effectuée !';
    CONST MSG_MODIFY='La modification est effectuée !';
    CONST MSG_ERROR='Une erreur est survenue, merci de corriger : ';
    CONST MSG_DELETE='La suppression est effectuée !';
    CONST MSG_DELETE_DANGER='Une erreur est intervenue, la suppression n\'a pas eu lieu !';

    const FORM = 'form';

    /**
     * @return Response
     */
    public function edit(
        Request $request,
        EntityInterface $entity,
        ManagerInterface $manager,
        string $domaine,
        string $domaineClass,
        string $message=self::MSG_MODIFY): Response
    {
        $form = $this->createForm($domaineClass, $entity);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($manager->save($entity)) {
                $this->addFlash(self::SUCCESS, $message);

                return $this->redirectToRoute($domaine . '_index');
            }
            $this->addFlash(self::DANGER, self::MSG_ERROR . $manager->getErrors($entity));
        }

        return $this->render($domaine. '/' .
            ($message===self::MSG_CREATE ? 'new' :  'edit') . '.html.twig', [
            $domaine => $entity,
            self::FORM => $form->createView(),
        ]);
    }

    /**
     * @return Response
     */
    public function delete(
        Request $request,
        EntityInterface $entity,
        ManagerInterface $manager,
        string $domaine): Response
    {
        if ($this->isCsrfTokenValid('delete'.$entity->getId(), $request->request->get('_token'))) {
            $this->addFlash(self::SUCCESS, self::MSG_DELETE);
            $manager->remove($entity);
        }
        return $this->redirectToRoute($domaine . '_index');
    }
}