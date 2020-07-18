<?php

namespace App\Controller;

use App\Entity\CityList;
use App\Form\CityListType;
use App\Repository\CityListRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/city/list")
 */
class CityListController extends AbstractController
{
    /**
     * @Route("/", name="city_list_index", methods={"GET"})
     */
    public function index(CityListRepository $cityListRepository): Response
    {
        return $this->render('city_list/index.html.twig', [
            'city_lists' => $cityListRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="city_list_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $cityList = new CityList();
        $form = $this->createForm(CityListType::class, $cityList);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($cityList);
            $entityManager->flush();

            return $this->redirectToRoute('city_list_index');
        }

        return $this->render('city_list/new.html.twig', [
            'city_list' => $cityList,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="city_list_show", methods={"GET"})
     */
    public function show(CityList $cityList): Response
    {
        return $this->render('city_list/show.html.twig', [
            'city_list' => $cityList,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="city_list_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, CityList $cityList): Response
    {
        $form = $this->createForm(CityListType::class, $cityList);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('city_list_index');
        }

        return $this->render('city_list/edit.html.twig', [
            'city_list' => $cityList,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="city_list_delete", methods={"DELETE"})
     */
    public function delete(Request $request, CityList $cityList): Response
    {
        if ($this->isCsrfTokenValid('delete'.$cityList->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($cityList);
            $entityManager->flush();
        }

        return $this->redirectToRoute('city_list_index');
    }
}
