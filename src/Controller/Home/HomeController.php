<?php

namespace App\Controller\Home;

use App\Dto\ActionSearchDto;
use App\Dto\DeployementSearchDto;
use App\Repository\ActionRepository;
use App\Repository\AxeRepository;
use App\Repository\DeployementRepository;
use App\Repository\MessageRepository;
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

     */
    public function homeAction(
        AxeRepository $axeRepository,
        MessageRepository $messageRepository
    ): Response
    {
        return $this->render('home/home.html.twig',
            [
                'axes' => $axeRepository->findAllForHome(),
                'messages' => $messageRepository->findBy(['name' => 'home'])
            ]);
    }


    /**
     * @Route("/search/", name="home_search", methods={"GET","POST"})
     * @IsGranted("ROLE_USER")
     */
    public function homeSearchAction(
        ActionRepository $actionrepo,
        ActionSearchDto $actionSearchDto,
        DeployementRepository $depRepo,
        DeployementSearchDto $deployementSearchDto,
        Request $request
    ): Response
    {

        $actionSearchDto->setSearch($request->request->get('search'));
        $deployementSearchDto->setSearch($request->request->get('search'));

        if (!$this->isGranted('ROLE_GESTIONNAIRE')) {
            if ($this->isGranted('ROLE_GESTIONNAIRE_LOCAL')) {
                $organismes = [];
                foreach ($this->getUser()->getOrganismes() as $organisme) {
                    $organismes = array_merge($organismes, [$organisme->getId()]);
                }

                $deployementSearchDto->setOrganismesId($organismes);
            } else {
                $deployementSearchDto->setUserWriter($this->getUser()->getId());
            }
        }

        return $this->render(
            'home/search.html.twig',
            [
                'actions'
                =>
                    $actionrepo->findAllForDto($actionSearchDto, ActionRepository::FILTRE_DTO_INIT_SEARCH),
                'deployements'
                =>
                    $depRepo->findAllForDto($deployementSearchDto),
            ]);
    }

}
