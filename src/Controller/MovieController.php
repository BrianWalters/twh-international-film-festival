<?php


namespace App\Controller;

use App\Entity\Movie;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


class MovieController extends AbstractController
{
    /**
     * @Route("/movie/{movie}", name="movie_details")
     */
    public function movieDetailsAction(Movie $movie)
    {
        return $this->render('movie.html.twig', [
            'movie' => $movie,
        ]);
    }
}