<?php

namespace App\Controller\Admin;

use App\Controller\AppControllerAbstract;
use App\Entity\Thematique;
use App\Form\Admin\ThematiqueType;
use App\Repository\ThematiqueRepository;
use App\Manager\ThematiqueManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/admin/thematique")
 */
class ThematiqueController extends AppControllerAbstract
{
    CONST ENTITYS='thematiques';
    CONST ENTITY='thematique';

    /**
     * @Route("/", name="thematique_index", methods={"GET"})
     * @return Response
     * @IsGranted("ROLE_USER")
     */
    public function indexAction(ThematiqueRepository $repository): Response
    {
        return $this->render(self::ENTITY . '/index.html.twig', [
            self::ENTITYS => $repository->findAllForAdmin(),
        ]);
    }

    /**
     * @Route("/new", name="thematique_new", methods={"GET","POST"})
     * @return Response
     * @IsGranted("ROLE_GESTIONNAIRE")
     */
    public function newAction(Request $request, ThematiqueManager $manager): Response
    {
        return $this->editAction($request,new Thematique(),$manager,self::MSG_CREATE);
    }

    /**
     * @Route("/{id}", name="thematique_show", methods={"GET"})
     * @return Response
     * @IsGranted("ROLE_USER")
     */
    public function showAction(Thematique $entity): Response
    {
        return $this->render(self::ENTITY .'/show.html.twig', [
            self::ENTITY => $entity,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="thematique_edit", methods={"GET","POST"})
     * @return Response
     * @IsGranted("ROLE_GESTIONNAIRE")
     */
    public function editAction(Request $request, Thematique $entity, ThematiqueManager $manager, string $message=self::MSG_MODIFY): Response
    {
        return $this->edit(
            $request,
            $entity,
            $manager,
            self::ENTITY,
            ThematiqueType::class,
            $message
        );
    }

    /**
     * @Route("/{id}", name="thematique_delete", methods={"DELETE"})
     * @return Response
     * @IsGranted("ROLE_GESTIONNAIRE")
     */
    public function deleteAction(Request $request, Thematique $entity, ThematiqueManager $manager): Response
    {
        return $this->delete($request,$entity,$manager,self::ENTITY);
    }
}
