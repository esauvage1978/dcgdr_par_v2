<?php

namespace App\Controller\Ajax;

use App\Controller\AppControllerAbstract;
use App\Repository\AxeRepository;
use App\Repository\CategoryRepository;
use App\Repository\PoleRepository;
use App\Repository\ThematiqueRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FillComboboxController extends AppControllerAbstract
{
    /**
     * @Route("/ajax/getaxes", name="ajax_fill_combobox_axes", methods={"POST"})
     *
     * @return Response
     * @IsGranted("ROLE_USER")
     */
    public function AjaxGetAxes(Request $request, AxeRepository $axeRepository): Response
    {
        if ($request->isXmlHttpRequest()) {
            return $this->json(
                $axeRepository->findAllFillCombobox(
                    $request->request->get('enable'),
                    $request->request->get('archiving')
                ));
        }

        return new Response("Ce n'est pas une requête Ajax");
    }

    /**
     * @Route("/ajax/getpoles", name="ajax_fill_combobox_poles", methods={"POST"})

     * @return Response
     * @IsGranted("ROLE_USER")
     */
    public function AjaxGetPolesForAxe(Request $request, PoleRepository $poleRepository): Response
    {
        if ($request->isXmlHttpRequest()) {
            return $this->json(
                $poleRepository->findAllFillComboboxForAxe(
                    $request->request->get('id'),
                    null === $request->request->get('enable') ? 'all' : $request->request->get('enable')
                ));
        }

        return new Response("Ce n'est pas une requête Ajax");
    }

    /**
 * @Route("/ajax/getthematiques", name="ajax_fill_combobox_thematiques", methods={"POST"})
 * @return Response
 * @IsGranted("ROLE_USER")
 */
    public function AjaxGetThematiquesForPole(Request $request, ThematiqueRepository $thematiqueRepository): Response
    {
        if ($request->isXmlHttpRequest()) {
            return $this->json(
                $thematiqueRepository->findAllFillComboboxForPole(
                    $request->request->get('id'),
                    null === $request->request->get('enable') ? 'all' : $request->request->get('enable')
                ));
        }

        return new Response("Ce n'est pas une requête Ajax");
    }

    /**
     * @Route("/ajax/getcategories", name="ajax_fill_combobox_categories", methods={"POST"})
     * @return Response
     * @IsGranted("ROLE_USER")
     */
    public function AjaxGetCategoriesForThematique(Request $request, CategoryRepository $categoryRepository): Response
    {
        if ($request->isXmlHttpRequest()) {
            return $this->json(
                $categoryRepository->findAllFillComboboxForThematique(
                    $request->request->get('id'),
                    null === $request->request->get('enable') ? 'all' : $request->request->get('enable')
                ));
        }

        return new Response("Ce n'est pas une requête Ajax");
    }
}
