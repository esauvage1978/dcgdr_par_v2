<?php

namespace App\Controller\Admin;

use App\Controller\AppControllerAbstract;
use App\Entity\User;
use App\Form\Admin\UserType;
use App\Repository\UserRepository;
use App\Manager\UserManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/user")
 */
class UserController extends AppControllerAbstract
{
    const ENTITYS = 'users';
    const ENTITY = 'user';

    /**
     * @Route("/", name="user_index", methods={"GET"})
     *
     * @return Response
     *
     * @IsGranted("ROLE_GESTIONNAIRE")
     *
     * @var UserRepository $userRepository
     */
    public function indexAction(UserRepository $userRepository): Response
    {
        return $this->render(self::ENTITY.'/index.html.twig', [
            self::ENTITYS => $userRepository->findAllForAdmin(),
        ]);
    }

    /**
     * @Route("/new", name="user_new", methods={"GET","POST"})
     *
     * @return Response
     *
     * @IsGranted("ROLE_GESTIONNAIRE")
     */
    public function newAction(Request $request, UserManager $manager): Response
    {
        return $this->editAction($request, new User(), $manager, self::MSG_CREATE);
    }

    /**
     * @Route("/{id}", name="user_show", methods={"GET"})
     *
     * @return Response
     * @IsGranted("ROLE_USER")
     */
    public function showAction(User $user): Response
    {
        return $this->render(self::ENTITY.'/show.html.twig', [
            self::ENTITY => $user,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="user_edit", methods={"GET","POST"})
     *
     * @param Request     $request
     * @param User        $user
     * @param UserManager $manager
     * @param string      $message =self::MSG_CREATE
     *
     * @return Response
     *
     * @IsGranted("ROLE_GESTIONNAIRE")
     */
    public function editAction(Request $request, User $user, UserManager $manager, string $message = self::MSG_MODIFY): Response
    {
        $userClone = clone $user;

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($manager->save($user, $userClone)) {
                $this->addFlash(self::SUCCESS, $message);

                return $this->redirectToRoute(self::ENTITY.'_index');
            }
            $this->addFlash(self::DANGER, self::MSG_ERROR.$manager->getErrors($user));
        }

        return $this->render(self::ENTITY.'/'.
            (self::MSG_CREATE === $message ? 'new' : 'edit').'.html.twig', [
            self::ENTITY => $user,
            self::FORM => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="user_delete", methods={"DELETE"})
     *
     * @return Response
     *
     * @IsGranted("ROLE_GESTIONNAIRE")
     */
    public function deleteAction(Request $request, User $user, UserManager $Manager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $this->addFlash(self::SUCCESS, self::MSG_DELETE);
            $Manager->remove($user);
        } else {
            $this->addFlash(self::DANGER, self::MSG_DELETE_DANGER);
        }

        return $this->redirectToRoute(self::ENTITY.'_index');
    }
}
