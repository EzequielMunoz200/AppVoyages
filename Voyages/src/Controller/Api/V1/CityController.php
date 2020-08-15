<?php

namespace App\Controller\Api\V1;

use App\Controller\TokenAuthenticatedController;
use App\Entity\City;
use App\Entity\CityLike;
use App\Repository\CityLikeRepository;
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
class CityController extends AbstractController /* implements TokenAuthenticatedController */
{

    /**
     * @Route("/city", name="city_list", methods={"GET"})
     */
    public function list(CityRepository $cityRepository, ObjectNormalizer $objetNormalizer, Request $request)
    {
        $search = $request->query->get('city_name');

        $cities = $this->getDoctrine()->getRepository(City::class)->findByPartialName($search);
        if (empty($cities)) {
            return new Response('Pas de résultats', Response::HTTP_NO_CONTENT);
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

            return new Response('Pas de résultats', Response::HTTP_NO_CONTENT);
        }

        /*  $serializer = new Serializer([new DateTimeNormalizer(), $objetNormalizer]);
        $json = $serializer->normalize($cityImage, null, ['groups' => 'api_v1_city']); */

        return $this->json($cityDetails);
    }

     /**
     * @Route("/image/{cityName}", name="image_city", methods={"GET"})
     */
    public function cityImage(CityRepository $cityRepository, ObjectNormalizer $objetNormalizer, Request $request, QueryApi $queryApi, $cityName)
    {

        $cityImagePortrait = $queryApi->cityDataImagePortrait($cityName);


        if (empty($cityImagePortrait)) {

            return new Response('Pas de résultats', Response::HTTP_NO_CONTENT);
        }

        /*  $serializer = new Serializer([new DateTimeNormalizer(), $objetNormalizer]);
        $json = $serializer->normalize($cityImage, null, ['groups' => 'api_v1_city']); */

        return $this->json($cityImagePortrait);
    }

     /**
     * @Route("/city/{id}/like", name="city_like", requirements={"id"="\d+"}, methods={"POST"})
     */
    public function like(City $city, CityLikeRepository $cityLikeRepository): Response
    {
        $user = $this->getUser();

        if (!$user) {

            return $this->json(403);
        }

        if ($city->isLikedByUser($user)) {
            $like = $cityLikeRepository->findOneBy([
                'city' => $city,
                'user' => $user,
            ]);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($like);
            $entityManager->flush();

            return $this->json([
                'code' => 200,
                'message' => 'like removed',
                'likes' => $cityLikeRepository->count(['city' => $city]),

            ], 200);
        }

        $like = new CityLike();
        $like->setCity($city);
        $like->setUser($user);
      

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($like);
        $entityManager->flush();


        return $this->json([
            'code' => 201,
            'message' => 'City liked',
            'likes' => $cityLikeRepository->count(['city' => $city]),
        ], 200);
    }
}
