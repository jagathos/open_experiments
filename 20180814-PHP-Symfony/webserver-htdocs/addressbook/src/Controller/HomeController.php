<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Person;
use App\Form\PersonType;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home", methods={"GET"})
     */
    public function index(Request $request): Response
    {
        $person = new Person();
        $person_form = $this->createForm(
            PersonType::class,
            $person
        );
        $person_form->handleRequest($request);

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'person_form' => $person_form->createView(),
        ]);
    }
}
