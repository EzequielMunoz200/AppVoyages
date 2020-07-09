<?php

namespace App\Controller\Api\V1;

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
class CityController extends AbstractController
{
   
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