<?php

namespace App\Controller;

use App\Entity\Picture;
use App\Entity\Review;
use App\Form\ReviewType;
use App\Repository\ReviewRepository;
use App\Service\ImageUploader;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/review")
 */
class ReviewController extends AbstractController
{
    /**
     * @Route("/", name="review_index", methods={"GET"})
     */
    public function index(ReviewRepository $reviewRepository): Response
    {
        return $this->render('review/index.html.twig', [
            'reviews' => $reviewRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="review_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $review = new Review();
        $formReview = $this->createForm(ReviewType::class, $review);
        $formReview->handleRequest($request);

        if ($formReview->isSubmitted() && $formReview->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($review);
            $entityManager->flush();

            return $this->redirectToRoute('review_index');
        }

        return $this->render('review/new.html.twig', [
            'review' => $review,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="review_show", methods={"GET"})
     */
    public function show(Review $review): Response
    {
        return $this->render('review/show.html.twig', [
            'review' => $review,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="review_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Review $review, ImageUploader $imageUploader): Response
    {
        $form = $this->createForm(ReviewType::class, $review);
        $form->handleRequest($request);
        //TODO
        $idPicture = '';
        foreach($review->getPictures() as $key => $objectPicture ){
            if($key == 0){
                $idPicture = $objectPicture->getId();
            }
        }

        if ($form->isSubmitted() && $form->isValid()) {
            
            if ($form->get('imageFile')->getData()) {
                
                $filename = $imageUploader->moveFile($form->get('imageFile')->getData(), 'images/uploads');
                $picture = new Picture();
                $em = $this->getDoctrine()->getManager();
                $picturesCollection = $em->getRepository(Picture::class)->findByReviewId($review->getId());
                //remove old pictures
                foreach($picturesCollection as $objectPicture ){
                    $em->remove($objectPicture);
                }

                if ($form->get('title')->getData() === null) {
                    $picture->setTitle($review->getCity()->getName());
                } else {
                    $picture->setTitle($form->get('title')->getData());
                }
                $picture->setFilename($filename);
                $picture->setCreatedAt(new \DateTime());
                $picture->setReview($review);
                $review->addPicture($picture);
                $review->setUpdatedAt(new \DateTime());
                $em->persist($picture);
                
                
            }
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('city_show', ['geonameId' =>  $review->getCity()->getGeonameId()]);
        }

        return $this->render('review/edit.html.twig', [
            'idPicture' => $idPicture,
            'review' => $review,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="review_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Review $review): Response
    {
        if ($this->isCsrfTokenValid('delete'.$review->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($review);
            $entityManager->flush();

            $this->addFlash(
                'success',
                'L\'avis a été effacé!'
            );
        }

        return $this->redirectToRoute('city_show', ['geonameId' =>  $review->getCity()->getGeonameId()]);
    }
}
