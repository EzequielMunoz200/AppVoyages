<?php

namespace App\Controller\Api\V1;

use App\Controller\TokenAuthenticatedController;
use App\Entity\City;
use App\Repository\CityRepository;
use App\Service\QueryApi;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Validator\Constraints\Date;

/**
 * @Route("/api/v1", name="api_v1_")
 */
class CityController extends AbstractController implements TokenAuthenticatedController
{

     /**
     * @Route("/city", name="city_list", methods={"GET"})
     */
    public function list(CityRepository $cityRepository, ObjectNormalizer $objetNormalizer, Request $request)
    {

        //$cities = $cityRepository->findAll();
        //$form->get('plainPassword')->getData()
        $search = $request->query->get('city_name');

        $cities = $this->getDoctrine()->getRepository(City::class)->findByPartialName($search);

        if (empty($cities)) {

            return new Response('Pas de resultats', Response::HTTP_NO_CONTENT);
        }

        $serializer = new Serializer([new DateTimeNormalizer(), $objetNormalizer]);

        $json = $serializer->normalize($cities, null, ['groups' => 'api_v1_city']);

        return $this->json($json);
    }
   
    /**
     * @Route("/city/{geonameId}", name="description_city", methods={"GET"})
     */
    public function cityDetails(ObjectNormalizer $objetNormalizer, Request $request, QueryApi $queryApi, $geonameId)
    {

        $cityDetails = $queryApi->cityDataDetails($geonameId);


        if (empty($cityDetails)) {

            return new Response('Pas de resultats', Response::HTTP_NO_CONTENT);
        }

        /*  $serializer = new Serializer([new DateTimeNormalizer(), $objetNormalizer]);
        $json = $serializer->normalize($cityImage, null, ['groups' => 'api_v1_city']); */

        return $this->json($cityDetails);
    }



    

   
}