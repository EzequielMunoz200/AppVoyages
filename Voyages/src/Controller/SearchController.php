<?php

namespace App\Controller;

use App\Data\AdvancedSearchData;
use App\Form\AdvancedSearchType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController
{
    /**
     * @Route("/search", name="search", methods={"GET"})
     */
    public function index()
    {
        
        return $this->render('search/search.html.twig', [
            
        ]);
    }

}