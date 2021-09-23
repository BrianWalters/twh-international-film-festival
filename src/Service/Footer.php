<?php


namespace App\Service;


use Symfony\Component\Routing\RouterInterface;

class Footer
{
    private RouterInterface $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    public function getLinks(): array
    {
        return [
            [
                'text' => 'All past movies',
                'url' => $this->router->generate('movie_past_index'),
            ],
            [
                'text' => 'Feast of the Flesh 9',
                'url' => 'http://feast-of-the-flesh-9.bdubcodes.net/',
            ],
            [
                'text' => 'Feast of the Flesh 10',
                'url' => 'http://feast-of-the-flesh-10.bdubcodes.net/',
            ]
        ];
    }
}