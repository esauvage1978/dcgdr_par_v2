<?php

namespace App\Controller\Admin;

use App\Controller\AppControllerAbstract;
use App\Entity\Organisme;
use App\Form\Admin\OrganismeType;
use App\Repository\OrganismeRepository;
use App\Manager\OrganismeManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/organisme")
 */
class OrganismeController extends AppControllerAbstract
{
    CONST ENTITYS = 'organismes';
    CONST ENTITY = 'organisme';

    /**
     * @Route("/", name="organisme_index", methods={"GET"})
     * @return Response
     * @IsGranted("ROLE_USER")
     */
    public function indexAction(OrganismeRepository $organismeRepository): Response
    {
        return $this->render(self::ENTITY . '/index.html.twig', [
            self::ENTITYS => $organismeRepository->findAllForAdmin(),
        ]);
    }

    /**
     * @Route("/new", name="organisme_new", methods={"GET","POST"})
     * @return Response
     * @IsGranted("ROLE_GESTIONNAIRE")
     */
    public function newAction(Request $request, OrganismeManager $manager): Response
    {
        return $this->editAction($request, new Organisme(), $manager, self::MSG_CREATE);
    }

    /**
     * @Route("/{id}", name="organisme_show", methods={"GET"})
     * @return Response
     * @IsGranted("ROLE_USER")
     */
    public function showAction(Organisme $organisme): Response
    {
        return $this->render(self::ENTITY . '/show.html.twig', [
            self::ENTITY => $organisme,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="organisme_edit", methods={"GET","POST"})
     * @return Response
     * @IsGranted("ROLE_GESTIONNAIRE")
     */
    public function editAction(
        Request $request,
        Organisme $entity,
        OrganismeManager $manager,
        string $message = self::MSG_MODIFY): Response
    {
        return $this->edit(
            $request,
            $entity,
            $manager,
            self::ENTITY,
            OrganismeType::class,
            $message
        );
    }

    /**
     * @Route("/{id}", name="organisme_delete", methods={"DELETE"})
     * @return Response
     * @IsGranted("ROLE_GESTIONNAIRE")
     */
    public function deleteAction(
        Request $request,
        Organisme $organisme,
        OrganismeManager $organismeManager): Response
    {
        return $this->delete($request, $organisme, $organismeManager, self::ENTITY);
    }
}
