<?php

namespace App\Controller\Ajax;

use App\Controller\AppControllerAbstract;
use App\Repository\ActionRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ActionController extends AppControllerAbstract
{
    /**
     * @Route("/ajax/getactionss", name="ajax_get_actions_for_category", methods={"POST"})
     *
     * @return Response
     * @IsGranted("ROLE_USER")
     */
    public function AjaxGetActionsforCategoryForViewSmallCard(Request $request, ActionRepository $actionRepository): Response
    {
        if ($request->isXmlHttpRequest()) {
            return $this->json(
                $actionRepository->findAllActionsforCategoryForViewSmallCard(
                    $request->request->get('id')
                ));
        }

        return new Response("Ce n'est pas une requête Ajax");
    }
}
