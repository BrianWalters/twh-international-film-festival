<?php

namespace App\Controller;

use App\Repository\MovieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(MovieRepository $movieRepository)
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


        $year = (new \DateTime())->format('Y');

        return $this->render('home/index.html.twig', [
            'title' => "Travis Wayne Hurt International Film Festival $year",
            'description' => null,
            'movies' => $movies,
        ]);
    }
}
