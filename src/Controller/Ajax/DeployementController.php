<?php


namespace App\Controller\Ajax;

use App\Controller\AppControllerAbstract;
use App\Helper\DeployementFilter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DeployementController extends AppControllerAbstract
{
    /**
     * @Route("/ajax/my/deployement/count/{filter?}", name="ajax_my_deployement_count", methods={"POST"})
     *
     * @return Response
     *
     * @IsGranted("ROLE_USER")
     */
    public function ajaxMyDeployementCountAction(
        Request $request,
        DeployementFilter $deploiementFilter,
        ?string $filter): Response
    {
       ;
        if ($request->isXmlHttpRequest()) {
            return $this->json(
                $deploiementFilter->getData($filter)['nbr']);
        }

        return new Response("Ce n'est pas une requête Ajax");
    }


}