<?php

namespace App\Twig;

use App\Entity\Movie;
use App\OMDB\API;
use App\OMDB\OmdbMovie;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    private API $omdbApi;

    public function __construct(API $omdbApi)
    {
        $this->omdbApi = $omdbApi;
    }

    public function getFilters(): array
    {
        return [

        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('omdb', [$this, 'getOmdbMovie']),
        ];
    }

    public function getOmdbMovie(Movie $movie): OmdbMovie
    {
        return $this->omdbApi->getMovie($movie->getImdb());
    }
}
