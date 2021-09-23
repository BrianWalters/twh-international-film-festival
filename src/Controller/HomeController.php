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
        $movies = $movieRepository->findBy([], [
            'startTime' => 'ASC'
        ]);

        return $this->render('home/index.html.twig', [
            'movies' => $movies,
        ]);
    }
}
