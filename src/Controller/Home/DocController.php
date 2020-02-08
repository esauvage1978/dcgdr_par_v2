<?php

namespace App\Controller\Home;

use App\Repository\MessageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/")
 */
class DocController extends AbstractController
{
    /**
     * @Route("/doc", name="documentation", methods={"GET"})
     * @return Response
     */
    public function documentationAction(MessageRepository $messageRepository): Response
    {
        return $this->render('documentation/index.html.twig', ['messages'=> $messageRepository->findBy(['name'=>'doc'])]);
    }


}
