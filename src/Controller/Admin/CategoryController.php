<?php

namespace App\Controller\Admin;

use App\Controller\AppControllerAbstract;
use App\Entity\Category;
use App\Form\Admin\CategoryType;
use App\Repository\CategoryRepository;
use App\Manager\CategoryManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/admin/category")
 */
class CategoryController extends AppControllerAbstract
{
    CONST ENTITYS='categories';
    CONST ENTITY='category';

    /**
     * @Route("/", name="category_index", methods={"GET"})
     * @return Response
     * @IsGranted("ROLE_USER")
     */
    public function indexAction(CategoryRepository $repository): Response
    {
        return $this->render(self::ENTITY . '/index.html.twig', [
            self::ENTITYS => $repository->findAllForAdmin(),
        ]);
    }

    /**
     * @Route("/new", name="category_new", methods={"GET","POST"})
     * @return Response
     * @IsGranted("ROLE_GESTIONNAIRE")
     */
    public function newAction(Request $request, CategoryManager $manager): Response
    {
        return $this->editAction($request,new Category(),$manager,self::MSG_CREATE);
    }

    /**
     * @Route("/{id}", name="category_show", methods={"GET"})
     * @return Response
     * @IsGranted("ROLE_USER")
     */
    public function showAction(Category $entity): Response
    {
        return $this->render(self::ENTITY .'/show.html.twig', [
            self::ENTITY => $entity,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="category_edit", methods={"GET","POST"})
     * @return Response
     * @IsGranted("ROLE_GESTIONNAIRE")
     */
    public function editAction(Request $request, Category $entity, CategoryManager $manager, string $message=self::MSG_MODIFY): Response
    {
        return $this->edit(
            $request,
            $entity,
            $manager,
            self::ENTITY,
            CategoryType::class,
            $message
        );
    }

    /**
     * @Route("/{id}", name="category_delete", methods={"DELETE"})
     * @return Response
     * @IsGranted("ROLE_GESTIONNAIRE")
     */
    public function deleteAction(Request $request, Category $entity, CategoryManager $manager): Response
    {
        return $this->delete($request,$entity,$manager,self::ENTITY);
    }
}
