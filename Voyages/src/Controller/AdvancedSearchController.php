<?php

namespace App\Controller;

use App\Data\AdvancedSearchData;
use App\Entity\City;
use App\Form\AdvancedSearchType;
use App\Repository\CityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use RecursiveArrayIterator;
use RecursiveIteratorIterator;

class AdvancedSearchController extends AbstractController
{
    /**
     * @Route("/advanced-search", name="advanced_search", methods={"GET", "POST"})
     */
    public function index(Request $request, CityRepository $cityRepository, SessionInterface $session)
    {
        $em = $this->getDoctrine()->getManager();

        $data = new AdvancedSearchData();
        $form = $this->createForm(AdvancedSearchType::class, $data);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('countries')->getData()) {
                $queryCountry = $form->get('countries')->getData()->getCountry();
                //create a query builder
                $allCities = $em->getRepository(City::class)->findByCountryName($queryCountry);
                if (!$allCities) {
                    return $this->redirectToRoute('advanced_search');
                }
            } 
            else {
                //all cities of the db
                $allCities = $cityRepository->findAll();
            }

            $chosenTags = $form->get('tags')->getData();
            $arrayResults = [];
            $matchCity = [];
            foreach ($allCities as $city) {
                foreach ($chosenTags as $chosenTag) {
                    //matching of the tags
                    if ($city->getTags()->contains($chosenTag)) {
                        $arrayResults[$chosenTag->getName()][] = $city;
                        //all the cities that have a match
                        $matchCity[] = $city->getId(); //[1035, 1036, 1035, 1035 ]
                    }
                }
            }

            $matchCity = (array_count_values($matchCity)); // [1035 => 3 , 1036 => 1]      

            /* For each tag, we check if the city is in the tags array.
            We count the number of times a city matches a tag.
            Calculation of the value (Nb of tags selected by the user/Total number of tags)  */
            $arrayMatching = [];
            foreach ($matchCity as $key => $value) {
                $matchValue = $value / count($chosenTags) * 100;
                $objectCity = $em->getRepository(City::class)->find($key);
                $arrayMatching[] = [
                    'city' => $objectCity,
                    'value' => round($matchValue),
                ];
            }
            $arrayMatchingFlat = [];
            //https://stackoverflow.com/questions/1319903/how-to-flatten-a-multidimensional-array
            $it = new RecursiveIteratorIterator(new RecursiveArrayIterator($arrayMatching));
            foreach ($it as $v) {
                $arrayMatchingFlat[] = (int) $v;
            }

            //get results for each range
            //https://www.php.net/manual/en/function.array-count-values.php
            $quantityPerRange = (array_count_values($arrayMatchingFlat));

            if(empty($quantityPerRange)){
                $this->addFlash(
                    'danger',
                    'Il n\'y a pas de résultats'
                );
    
    
                return $this->redirectToRoute('advanced_search');
            }
    

            // redirects to a route and maintains the original query string parameters
            //https://symfony.com/doc/current/controller.html
            $session->set('arrayMatching', $arrayMatching);
            $session->set('urlResults',  $_SERVER['REQUEST_URI']);
            $session->set('quantityPerRange', $quantityPerRange);
            
         
            return $this->redirectToRoute('city_list_results');
        }


        return $this->render('search/advanced-search.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
