<?php

namespace App\Controller\Admin;

use App\Controller\AppControllerAbstract;
use App\Entity\Corbeille;
use App\Form\Admin\CorbeilleGestionnaireLocalType;
use App\Form\Admin\CorbeilleType;
use App\Manager\CorbeilleManager;
use App\Repository\CorbeilleRepository;
use App\Security\CorbeilleVoter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/corbeille")
 */
class CorbeilleController extends AppControllerAbstract
{
    const ENTITYS = 'corbeilles';
    const ENTITY = 'corbeille';

    /**
     * @Route("/", name="corbeille_index", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function indexAction(CorbeilleRepository $repository): Response
    {
        return $this->render(self::ENTITY.'/index.html.twig', [
            self::ENTITYS => $repository->findAllForAdmin(),
        ]);
    }

    /**
     * @Route("/new", name="corbeille_new", methods={"GET","POST"})
     * @IsGranted("ROLE_GESTIONNAIRE_LOCAL")
     */
    public function newAction(Request $request, CorbeilleManager $manager): Response
    {
        $this->denyAccessUnlessGranted(CorbeilleVoter::CREATE, null);
        return $this->editAction($request, new Corbeille(), $manager, self::MSG_CREATE);
    }

    /**
     * @Route("/{id}", name="corbeille_show", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function showAction(Corbeille $entity): Response
    {
        return $this->render(self::ENTITY.'/show.html.twig', [
            self::ENTITY => $entity,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="corbeille_edit", methods={"GET","POST"})
     * @IsGranted("ROLE_GESTIONNAIRE_LOCAL")
     */
    public function editAction(
        Request $request,
        Corbeille $entity,
        CorbeilleManager $manager,
        string $message = self::MSG_MODIFY): Response
    {
        $this->denyAccessUnlessGranted(CorbeilleVoter::UPDATE, $entity);

        if ($this->isgranted('ROLE_GESTIONNAIRE')) {
            $form = $this->createForm(CorbeilleType::class, $entity);
        } else {
            $form = $this->createForm(CorbeilleGestionnaireLocalType::class, $entity);
        }

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($manager->save($entity)) {
                $this->addFlash(self::SUCCESS, $message);

                return $this->redirectToRoute(self::ENTITY.'_index');
            }
            $this->addFlash(self::DANGER, self::MSG_ERROR.$manager->getErrors($entity));
        }

        return $this->render(self::ENTITY.'/'.
            (self::MSG_CREATE === $message ? 'new' : 'edit').'.html.twig', [
            self::ENTITY => $entity,
            self::FORM => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="corbeille_delete", methods={"DELETE"})
     * @IsGranted("ROLE_GESTIONNAIRE")
     */
    public function deleteAction(Request $request, Corbeille $entity, CorbeilleManager $manager): Response
    {
        $this->denyAccessUnlessGranted(CorbeilleVoter::DELETE, $entity);

        return $this->delete($request, $entity, $manager, self::ENTITY);
    }
}
