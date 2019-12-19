<?php

namespace App\Controller\Admin;

use App\Controller\AppControllerAbstract;
use App\Entity\Cible;
use App\Form\Admin\CibleType;
use App\Repository\CibleRepository;
use App\Manager\CibleManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/admin/cible")
 */
class CibleController extends AppControllerAbstract
{
    const ENTITYS = 'cibles';
    const ENTITY = 'cible';

    /**
     * @Route("/", name="cible_index", methods={"GET"})
     * @return Response
     * @IsGranted("ROLE_USER")
     */
    public function indexAction(CibleRepository $repository): Response
    {
        return $this->render(self::ENTITY.'/index.html.twig', [
            self::ENTITYS => $repository->findAllForAdmin(),
        ]);
    }

    /**
     * @Route("/new", name="cible_new", methods={"GET","POST"})
     * @return Response
     * @IsGranted("ROLE_GESTIONNAIRE")
     */
    public function newAction(Request $request, CibleManager $manager): Response
    {
        return $this->editAction($request, new Cible(), $manager, self::MSG_CREATE);
    }

    /**
     * @Route("/{id}", name="cible_show", methods={"GET"})
     * @return Response
     * @IsGranted("ROLE_USER")
     */
    public function showAction(Cible $entity): Response
    {
        return $this->render(self::ENTITY.'/show.html.twig', [
            self::ENTITY => $entity,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="cible_edit", methods={"GET","POST"})
     * @return Response
     * @IsGranted("ROLE_GESTIONNAIRE")
     */
    public function editAction(Request $request, Cible $entity, CibleManager $manager, string $message = self::MSG_MODIFY): Response
    {
        return $this->edit(
            $request,
            $entity,
            $manager,
            self::ENTITY,
            CibleType::class,
            $message
        );
    }

    /**
     * @Route("/{id}", name="cible_delete", methods={"DELETE"})
     * @return Response
     * @IsGranted("ROLE_GESTIONNAIRE")
     */
    public function deleteAction(Request $request, Cible $entity, CibleManager $manager): Response
    {
        return $this->delete($request, $entity, $manager, self::ENTITY);
    }
}
