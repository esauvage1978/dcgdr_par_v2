<?php

namespace App\Controller\Admin;

use App\Controller\AppControllerAbstract;
use App\Entity\Vecteur;
use App\Form\Admin\VecteurType;
use App\Repository\VecteurRepository;
use App\Manager\VecteurManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/admin/vecteur")
 */
class VecteurController extends AppControllerAbstract
{
    const ENTITYS = 'vecteurs';
    const ENTITY = 'vecteur';

    /**
     * @Route("/", name="vecteur_index", methods={"GET"})
     * @return Response
     * @IsGranted("ROLE_USER")
     */
    public function indexAction(VecteurRepository $repository): Response
    {
        return $this->render(self::ENTITY.'/index.html.twig', [
            self::ENTITYS => $repository->findAllForAdmin(),
        ]);
    }

    /**
     * @Route("/new", name="vecteur_new", methods={"GET","POST"})
     * @return Response
     * @IsGranted("ROLE_GESTIONNAIRE")
     */
    public function newAction(Request $request, VecteurManager $manager): Response
    {
        return $this->editAction($request, new Vecteur(), $manager, self::MSG_CREATE);
    }

    /**
     * @Route("/{id}", name="vecteur_show", methods={"GET"})
     * @return Response
     * @IsGranted("ROLE_USER")
     */
    public function showAction(Vecteur $entity): Response
    {
        return $this->render(self::ENTITY.'/show.html.twig', [
            self::ENTITY => $entity,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="vecteur_edit", methods={"GET","POST"})
     * @return Response
     * @IsGranted("ROLE_GESTIONNAIRE")
     */
    public function editAction(Request $request, Vecteur $entity, VecteurManager $manager, string $message = self::MSG_MODIFY): Response
    {
        return $this->edit(
            $request,
            $entity,
            $manager,
            self::ENTITY,
            VecteurType::class,
            $message
        );
    }

    /**
     * @Route("/{id}", name="vecteur_delete", methods={"DELETE"})
     * @return Response
     * @IsGranted("ROLE_GESTIONNAIRE")
     */
    public function deleteAction(Request $request, Vecteur $entity, VecteurManager $manager): Response
    {
        return $this->delete($request, $entity, $manager, self::ENTITY);
    }
}
