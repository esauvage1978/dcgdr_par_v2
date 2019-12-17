<?php


namespace App\Controller\Profil;

use App\Controller\AppControllerAbstract;
use App\Repository\UserRepository;
use App\Manager\UserManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/profil/avatar")
 * @IsGranted("ROLE_USER")
 */
class AvatarController extends AppControllerAbstract
{
    CONST DOMAINE='profil';

    /**
     * @Route("/", name="profil_avatar")
     *
     * @param Request $request
     * @param UserRepository $userRepository
     * @return Response
     */
    public function showAction(Request $request, UserRepository $userRepository): Response
    {
        return $this->render( self::DOMAINE . '/avatar.html.twig');
    }

    /**
     * @Route("/update", name="profil_avatar_update")
     *
     * @param Request $request
     * @param UserManager $userManager
     * @return Response
     */
    public function ajaxAction(Request $request, UserManager $userManager)
    {
        $user = $this->getUser();

        /* on récupère la valeur envoyée par la vue */
        $image = $request->request->get('dataImg');

        $userManager->avatarAdd($user, $image);
        $userManager->save($user);

        $response = new Response(json_encode([
            'retour' => 'Avatar mis à jour',
        ]));
        $response->headers->set('Content-Type', 'application/json');

        $this->addFlash(self::SUCCESS, self::MSG_MODIFY);

        return $response;
    }
}
