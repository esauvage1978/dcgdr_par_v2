<?php

namespace App\Controller\Home;

use App\Dto\ActionSearchDto;
use App\Repository\ActionRepository;
use App\Repository\AxeRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/")
 */
class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home", methods={"GET"})
     * @return Response
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     */
    public function homeAction(AxeRepository $axeRepository): Response
    {
        return $this->render('home/home.html.twig', ['axes' => $axeRepository->findAllForHome()]);
    }


    /**
     * @Route("/search/", name="home_search", methods={"GET","POST"})
     * @IsGranted("ROLE_USER")
     */
    public function homeSearchAction(
        ActionRepository $repository,
        ActionSearchDto $actionSearchDto,
        Request $request
    ): Response
    {

        $actionSearchDto->setSearch($request->request->get('search'));
        dump($actionSearchDto);
        return $this->render('home/search.html.twig', [
            'actions' => $repository->findAllForDto($actionSearchDto),
        ]);
    }

}
