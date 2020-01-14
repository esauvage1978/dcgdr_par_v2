<?php

namespace App\Controller\Ajax;

use App\Controller\AppControllerAbstract;
use App\Manager\IndicatorValueManager;
use App\Repository\DeployementRepository;
use App\Repository\IndicatorRepository;
use App\Repository\IndicatorValueRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndicatorValueController extends AppControllerAbstract
{
    /**
     * @Route("/ajax/create_deployement", name="ajax_create_deployement", methods={"POST"})
     *
     * @return Response
     *
     * @IsGranted("ROLE_USER")
     */
    public function AjaxCreateDeployement(
        Request $request,
        IndicatorValueManager $manager,
        IndicatorValueRepository $indicatorValuerepository,
        IndicatorRepository $indicatorRepository,
        DeployementRepository $deployementrepository): Response
    {
        if ($request->isXmlHttpRequest()) {
            $indicator = $indicatorRepository->findOneBy(['id' => $request->request->get('indicator_id')]);
            $deployement = $deployementrepository->findOneBy(['id' => $request->request->get('deployement_id')]);

            $indicatorValue = $indicatorValuerepository->findOneBy(
                [
                    'deployement' => $deployement,
                    'indicator' => $indicator,
                ]);

            $indicatorValue = $manager->initialiseEntity($indicator, $deployement, $indicatorValue);

            $manager->save($indicatorValue);

            return $this->json(
                $indicatorValue->getEnable());
        }

        return new Response("Ce n'est pas une requête Ajax");
    }
}
