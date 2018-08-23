<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Occupation;
use App\Repository\OccupationRepository;

class OccupationController extends AbstractController
{
    /**
     * @Route("/occupations", name="occupation", methods={"GET"})
     */
    public function index(OccupationRepository $occupationRepository)
    {
        $occupations = $occupationRepository->findAll();
        return $this->json($occupations);
    }
}
