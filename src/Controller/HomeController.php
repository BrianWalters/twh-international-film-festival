<?php

namespace App\Controller;

use App\Entity\Movie;
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

        $volumeNumber = $year - 2009;

        $description = implode(', ', array_map(fn(Movie $movie) => $movie->getTitle(), $movies));

        return $this->render('home/index.html.twig', [
            'title' => "Feast of the Flesh Fest Volume $volumeNumber",
            'description' => $description,
            'movies' => $movies,
            'volumeNumber' => $volumeNumber,
        ]);
    }
}
