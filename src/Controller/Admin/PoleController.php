<?php

namespace App\Controller\Admin;

use App\Controller\AppControllerAbstract;
use App\Entity\Pole;
use App\Form\Admin\PoleType;
use App\Repository\PoleRepository;
use App\Manager\PoleManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/admin/pole")
 */
class PoleController extends AppControllerAbstract
{
    CONST ENTITYS='poles';
    CONST ENTITY='pole';

    /**
     * @Route("/", name="pole_index", methods={"GET"})
     * @return Response
     * @IsGranted("ROLE_USER")
     */
    public function indexAction(PoleRepository $repository): Response
    {
        return $this->render(self::ENTITY . '/index.html.twig', [
            self::ENTITYS => $repository->findAllForAdmin(),
        ]);
    }

    /**
     * @Route("/new", name="pole_new", methods={"GET","POST"})
     * @IsGranted("ROLE_GESTIONNAIRE")
     */
    public function newAction(Request $request, PoleManager $manager): Response
    {
        return $this->editAction($request,new Pole(),$manager,self::MSG_CREATE);
    }

    /**
     * @Route("/{id}", name="pole_show", methods={"GET"})
     * @IsGranted("ROLE_USER")

     */
    public function showAction(Pole $entity): Response
    {
        return $this->render(self::ENTITY .'/show.html.twig', [
            self::ENTITY => $entity,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="pole_edit", methods={"GET","POST"})
     * @IsGranted("ROLE_GESTIONNAIRE")
     */
    public function editAction(Request $request, Pole $entity, PoleManager $manager, string $message=self::MSG_MODIFY): Response
    {
        return $this->edit(
            $request,
            $entity,
            $manager,
            self::ENTITY,
            PoleType::class,
            $message
        );
    }

    /**
     * @Route("/{id}", name="pole_delete", methods={"DELETE"})
     * @IsGranted("ROLE_GESTIONNAIRE")
     */
    public function deleteAction(Request $request, Pole $entity, PoleManager $manager): Response
    {
        return $this->delete($request,$entity,$manager,self::ENTITY);
    }
}
