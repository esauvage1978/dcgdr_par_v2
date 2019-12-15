<?php

namespace App\Controller\Admin;

use App\Controller\AppControllerAbstract;
use App\Entity\Axe;
use App\Form\Admin\AxeType;
use App\Manager\AxeManager;
use App\Repository\AxeRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/axe")
 */
class AxeController extends AppControllerAbstract
{
    const ENTITYS = 'axes';
    const ENTITY = 'axe';

    /**
     * @Route("/", name="axe_index", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function indexAction(AxeRepository $repository): Response
    {
        return $this->render(self::ENTITY.'/index.html.twig', [
            self::ENTITYS => $repository->findAllForAdmin(),
        ]);
    }

    /**
     * @Route("/new", name="axe_new", methods={"GET","POST"})
     * @IsGranted("ROLE_GESTIONNAIRE")
     */
    public function newAction(Request $request, AxeManager $manager): Response
    {
        return $this->editAction($request, new Axe(), $manager, self::MSG_CREATE);
    }

    /**
     * @Route("/{id}", name="axe_show", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function showAction(Axe $entity): Response
    {
        return $this->render(self::ENTITY.'/show.html.twig', [
            self::ENTITY => $entity,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="axe_edit", methods={"GET","POST"})
     * @IsGranted("ROLE_GESTIONNAIRE")
     */
    public function editAction(Request $request, Axe $entity, AxeManager $manager, string $message = self::MSG_MODIFY): Response
    {
        return $this->edit(
            $request,
            $entity,
            $manager,
            self::ENTITY,
            AxeType::class,
            $message
        );
    }

    /**
     * @Route("/{id}", name="axe_delete", methods={"DELETE"})
     * @IsGranted("ROLE_GESTIONNAIRE")
     */
    public function deleteAction(Request $request, Axe $entity, AxeManager $manager): Response
    {
        return $this->delete($request, $entity, $manager, self::ENTITY);
    }
}
