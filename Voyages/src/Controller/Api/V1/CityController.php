<?php

namespace App\Controller\Api\V1;

use App\Entity\City;
use App\Entity\CityLike;
use App\Repository\CityLikeRepository;
use App\Service\QueryApi;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/v1", name="api_v1_")
 */
class CityController extends AbstractController /* implements TokenAuthenticatedController */
{
     /**
     * @Route("/image/{cityName}", name="image_city", methods={"GET"})
     */
    public function cityImage(QueryApi $queryApi, $cityName)
    {

        $cityImagePortrait = $queryApi->cityDataImagePortrait($cityName);
        if (empty($cityImagePortrait)) {
            return new Response('Pas de résultats', Response::HTTP_NO_CONTENT);
        }

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