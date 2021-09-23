<?php

namespace App\Controller;

use App\OMDB\API;
use App\Repository\MovieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(MovieRepository $movieRepository, API $api)
    {
        $year = (int)(new \DateTime('now'))->format('Y');

        $movies = $movieRepository->findBy(
            [
                'yearFeasted' => $year,
            ],
            [
                'startTime' => 'ASC',
            ]
        );

        return $this->render('home/index.html.twig', [
            'movies' => $movies,
            'volumeNumber' => $year - 2009,
        ]);
    }
}
