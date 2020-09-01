<?php

namespace App\Controller;

use App\Entity\City;
use App\Entity\Picture;
use App\Entity\Review;
use App\Form\ReviewType;
use App\Service\QueryApi;
use App\Service\ImageUploader;
use App\Repository\CityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/city")
 */
class CityController extends AbstractController
{

    private $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    
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
     * @Route("/{geonameId}", name="city_show", requirements={"geonameId"="\d+"}, methods={"GET", "POST"})
     */
    public function show(ImageUploader $imageUploader, City $city, QueryApi $queryApi, $geonameId, Request $request): Response
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
            $review->setAuthor($this->getUser());

            $filename = $imageUploader->moveFile($formReview->get('imageFile')->getData(), 'images/uploads');
            $picture = new Picture();
            $entityManager = $this->getDoctrine()->getManager();
            if ($formReview->get('imageFile')->getData()) {
                if ($formReview->get('title')->getData() === null) {
                    $picture->setTitle($city->getCityName());
                } else {
                    $picture->setTitle($formReview->get('title')->getData());
                }
                $picture->setFilename($filename);
                $picture->setCreatedAt(new \DateTime());
                $picture->setReview($review);
                $review->addPicture($picture);
                $entityManager->persist($picture);
            }

            //gain 10 points for each review published
            $entityManager->persist($this->getUser()->setPoints(10));
            $entityManager->persist($review);
            $entityManager->flush();

            $this->addFlash(
                'success',
                'L\'avis a été publié!'
            );

            return $this->redirectToRoute('city_show', ['geonameId' =>  $geonameId]);
        }

        
        $this->session->set('lastCityVisited', $this->generateUrl('city_show', ['geonameId' =>  $geonameId]));


        return $this->render('city/show.html.twig', [
            'cityData' => $queryApi->citiesData($geonameId),
            'imagesData' => $queryApi->citiesDataImages($city->getName()),
            'imagesData' => $queryApi->citiesDataImages($queryApi->citiesData($geonameId)['cityNameUnsplash']),
            'cityImagePortrait' => $queryApi->cityDataImagePortrait($queryApi->citiesData($geonameId)['cityNameUnsplash']),
            'details' => $queryApi->cityDataDetails($geonameId),
            'reviews' => $city->getReviews(), //array à initialiser dans le template
            'formReview' => $formReview->createView(),
            'objectCity' => $city,
            'CityName' => $city->getName(),
        ]);
    }

    /**
     * @Route("/random", name="city_random", methods={"GET"})
     */
    public function getRandomCity(): Response
    {
        $randomCities = [];
        $randomCity = false;
        while (true) {
            $randomCity = $this->getDoctrine()->getRepository(City::class)->find(rand(1035, 2029));
            if ($randomCity) {
                $randomCities[] = $randomCity;
                if (count($randomCities) == 6) {
                    break;
                }
            }
        }

        return $this->render('city/random.html.twig', [
            'randomCities' => $randomCities,
        ]);
    }
}
