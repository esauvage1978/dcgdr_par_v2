<?php

namespace App\Controller\Ajax;

use App\Controller\AppControllerAbstract;
use App\Dto\ActionSearchDto;
use App\Helper\ActionFilter;
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

    /**
     * @Route("/ajax/countactionsforaxe/{axeid?}", name="ajax_action_for_axe_count", methods={"POST"})
     *
     * @return Response
     * @IsGranted("ROLE_USER")
     */
    public function AjaxGetCountActionsforAxe(
        Request $request,
        ActionSearchDto $actionSearchDto,
        ActionRepository $actionRepository,
        ?string $axeid): Response
    {
        $actionSearchDto->setAxeId($axeid);

        if ($request->isXmlHttpRequest()) {
            return $this->json(
                count($actionRepository->findAllForDto(
                    $actionSearchDto,ActionRepository::FILTRE_DTO_INIT_AJAX
                )));
        }

        return new Response("Ce n'est pas une requête Ajax");
    }

    /**
     * @Route("/ajax/workflow/actions/nbr/{state?}", name="ajax_actions_by_state", methods={"POST"})
     *
     * @param Request          $request
     * @param ActionRepository $actionRepository
     *
     * @return Response
     *
     * @IsGranted("ROLE_USER")
     *
     */
    public function ajaxActionsByStatAction(Request $request,ActionSearchDto $actionSearchDto, ActionRepository $actionRepository, ?string $state): Response
    {
        $actionSearchDto->setState($state);
        $resultRepo = $actionRepository->findAllForDto($actionSearchDto,ActionRepository::FILTRE_DTO_INIT_AJAX);

        if ($request->isXmlHttpRequest()) {
            return $this->json(
                count($resultRepo));
        }

        return new Response("Ce n'est pas une requête Ajax");
    }

    /**
     * @Route("/ajax/my/action/count/{filter?}", name="ajax_my_action_count", methods={"POST"})
     *
     * @return Response
     *
     * @IsGranted("ROLE_USER")
     */
    public function ajaxMyActionCountAction(
        Request $request,
        ActionFilter $actionFilter,
        ?string $filter): Response
    {
        ;
        if ($request->isXmlHttpRequest()) {
            return $this->json(
                $actionFilter->getData($filter)['nbr']);
        }

        return new Response("Ce n'est pas une requête Ajax");
    }
}
