<?php

namespace App\Controller;

use App\Entity\CityList;
use App\Entity\User;
use App\Form\AdvancedSearchType;
use App\Form\CityListType;
use App\Repository\CityListRepository;
use App\Repository\CityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/city/list")
 */
class CityListController extends AbstractController
{

    private $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

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
     * @Route("/results", name="city_list_results", methods={"GET","POST"})
     */
    public function showResults(Request $request, CityRepository $cityRepository, SessionInterface $session): Response
    {

       
        $arrayMatching = $session->get('arrayMatching');
        $urlResults = $session->get('urlResults');
        $quantityPerRange = $session->get('quantityPerRange');

        if(!$quantityPerRange){
            return $this->redirectToRoute('accueil');
        }

        $cityList = new CityList();
        $form = $this->createForm(CityListType::class, $cityList);
        $form->handleRequest($request);

        dump($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            //add current user
            $cityList->addUser($this->getUser());
            //add url 
            $cityList->setUrl($urlResults);
            $entityManager->persist($cityList);
            $entityManager->flush();
            $this->addFlash(
                'success',
                'La liste a été sauvegardée!'
            );

            return $this->redirectToRoute('city_list_results');
        }

        return $this->render('city_list/show.html.twig', [
            'arrayMatching' => $arrayMatching,
            'urlResults' => $_SERVER['SERVER_NAME'].$urlResults,
            'quantityPerRange' => $quantityPerRange,
            'city_list' => $cityList,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="city_list_show", methods={"GET"})
     */
    public function show($id): Response
    {

        $user = $this->getDoctrine()->getRepository(User::class)->find($id);

        if ($user != $this->getUser()) {
            throw $this->createNotFoundException('Cette page n\'existe pas');
        }

        return $this->render('city_list/index.html.twig', []);
    
        /* return $this->render('city_list/show.html.twig', [
            'city_list' => $cityList,
            //'resultat' => 
        ]); */
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
        if ($this->isCsrfTokenValid('delete' . $cityList->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($cityList);
            $entityManager->flush();
        }

        return $this->redirectToRoute('city_list_index');
    }
}
