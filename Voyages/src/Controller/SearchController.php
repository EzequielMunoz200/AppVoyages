<?php

namespace App\Controller;

use App\Data\AdvancedSearchData;
use App\Entity\City;
use App\Form\AdvancedSearchType;
use App\Form\SearchType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController
{
    /**
     * @Route("/search", name="search", methods={"GET"})
     */
    public function index(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(SearchType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            return $this->redirectToRoute('city_show', [
                'geonameId' => $form->get('cities')->getData()->getGeonameId(),
            ]);
        }
        
        return $this->render('search/search.html.twig', [
            'form' => $form->createView(),
        ]);
       
    }

}