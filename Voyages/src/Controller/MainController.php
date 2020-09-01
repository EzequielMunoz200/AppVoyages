<?php

namespace App\Controller;

use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;



class MainController extends AbstractController
{

    /**
     * @Route("/", name="accueil", methods={"GET"})
     */
    public function home()
    {
        return $this->render('main/home.html.twig', [
            'pageTitle' => 'Accueil',
        ]);
    }

    /**
     * @Route("/legal-mentions", name="legal_mentions", methods={"GET"})
     */
    public function legal()
    {
        return $this->render('main/legal.html.twig', [
            'pageTitle' => 'Legal',
        ]);
    }

    /**
     * @Route("/contact", name="contact", methods={"GET", "POST"})
     */
    public function contact(Request $request)
    {

        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('advanced_search');
        }



        return $this->render('main/contact.html.twig', [
            'pageTitle' => 'Contact',
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/about", name="about", methods={"GET"})
     */
    public function about()
    {
        return $this->render('main/about.html.twig', [
            'pageTitle' => 'About',
        ]);
    }
}