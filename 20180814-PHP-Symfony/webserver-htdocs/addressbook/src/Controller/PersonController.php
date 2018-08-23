<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Entity\Person;                                                         
use App\Form\PersonType;
use App\Repository\PersonRepository;

/**
 * @Route("/persons")
 */
class PersonController extends Controller
{
    /**
     * @Route("", name="person_index", methods={"GET"})
     */
    public function index(Request $request, PersonRepository $personRepository): Response
    {
      $persons = $personRepository->findAll();
      return $this->json($persons);
    }
    /**
     * @Route("", name="person_create", methods={"POST"})
     */
    public function create(Request $request, ValidatorInterface $validator): Response
    {
        $result = array('status' => 'failure');
        $person = new Person();
        $form = $this->createForm(PersonType::class, $person, array('method' => 'POST'));
        $form->handleRequest($request);

        $errors = $validator->validate($person);
        if(count($errors) > 0) {
            $result['message'] = $this->formatErrorMessages($errors);
        } elseif ($form->isSubmitted() && $form->isValid()) {
            $person->setCreatetimestamp(new \DateTime());
            $em = $this->getDoctrine()->getManager();
            $em->persist($person);
            $em->flush();
            $result = array('status' => 'success', 'data' => $person);
        }
        return $this->json($result);
    }
    /**
     * @Route("/{id}", name="person_read", methods={"GET"})
     */
    public function read(Request $request, Person $person): Response
    {
      return $this->json($person);
    }
    /**
     * @Route("/{id}", name="person_update", methods={"PUT"})
     */
    public function update(Request $request, Person $person, ValidatorInterface $validator): Response
    {
        $result = array('status' => 'failure');
        $form = $this->createForm(PersonType::class, $person, array('method' => 'PUT'));
        $form->handleRequest($request);

        $errors = $validator->validate($person);
        if(count($errors) > 0) {
            $result['message'] = $this->formatErrorMessages($errors);
        } elseif ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            $result = array('status' => 'success', 'data' => $person);
        }
        return $this->json($result);
    }
    /**
     * @Route("/{id}", name="person_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Person $person): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($person);
        $entityManager->flush();

        $result = array('status' => 'success');
        return $this->json($result);
    }

    private function formatErrorMessages($validationErrors) {
        $message = "";
        foreach($validationErrors as $error) {
            $message = $message."- ".$error->getMessage()."\n";
        }
        return $message;
    }
}
