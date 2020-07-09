<?php

namespace App\Controller;

use App\Entity\City;
use App\Entity\Picture;
use App\Entity\Review;
use App\Form\CityType;
use App\Form\ReviewType;
use App\Service\QueryApi;
use App\Service\ImageUploader;
use App\Repository\CityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/city")
 */
class CityController extends AbstractController
{
    /**
     * @Route("/", name="city_index", methods={"GET"})
     */
    public function index(CityRepository $cityRepository): Response
    {
        return $this->render('city/index.html.twig', [
            'cities' => $cityRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="city_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $city = new City();
        $form = $this->createForm(CityType::class, $city);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($city);
            $entityManager->flush();

            return $this->redirectToRoute('city_index');
        }

        return $this->render('city/new.html.twig', [
            'city' => $city,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{geonameId}", name="city_show", requirements={"geonameId"="\d+"}, methods={"GET", "POST"})
     */
    public function show(City $city, QueryApi $queryApi, $geonameId, Request $request): Response
    {

        $city = $this->getDoctrine()->getRepository(City::class)->findbyGeonameID($geonameId);

        if (!$city) {
            throw $this->createNotFoundException('Cette page n\'existe pas');
        }

        //form review
        $review = new Review();
        $formReview = $this->createForm(ReviewType::class, $review);
        $formReview->handleRequest($request);

        if ($formReview->isSubmitted() && $formReview->isValid()) {
            $review->setCity($city);
            // Active => true| Inactive => false
            $review->setIsActive(true);
            // is not reported => false | is reported => true
            $review->setIsReported(false);
            $review->setRate($formReview->get('rate')->getData());

            $review->setCreatedAt(new \DateTime());

            $filename = $imageUploader->moveFile($formReview->get('imageFile')->getData(), 'images/');
            $picture = new Picture();
            $title = $formReview->get('title')->getData();
            $picture->setTitle($title);
            $picture->setFilename($filename);
            $picture->setCreatedAt(new \DateTime());
            $picture->setReview($review);

            $review->addPicture($picture);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($picture);
            $entityManager->persist($review);
            $entityManager->flush();

            return $this->redirectToRoute('city_show', ['geonameId' =>  $geonameId]);
        }



        return $this->render('city/show.html.twig', [
            'cityData' => $queryApi->citiesData($geonameId),
            'imagesData' => $queryApi->citiesDataImages($queryApi->citiesData($geonameId)['cityNameUnsplash']),
            'details' => $queryApi->cityDataDetails($geonameId),
            'reviews' => $city->getReviews(), //array à initialiser dans le template
            'formReview' => $formReview->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="city_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, City $city): Response
    {
        $form = $this->createForm(CityType::class, $city);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('city_index');
        }

        return $this->render('city/edit.html.twig', [
            'city' => $city,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="city_delete", methods={"DELETE"})
     */
    public function delete(Request $request, City $city): Response
    {
        if ($this->isCsrfTokenValid('delete' . $city->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($city);
            $entityManager->flush();
        }

        return $this->redirectToRoute('city_index');
    }
}
