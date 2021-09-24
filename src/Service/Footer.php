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
                'url' => 'https://nine.feastofthefleshfest.app',
            ],
            [
                'text' => 'Feast of the Flesh 10',
                'url' => 'https://ten.feastofthefleshfest.app',
            ],
            [
                'text' => 'Feast of the Flesh 11',
                'url' => 'https://eleven.feastofthefleshfest.app',
            ]
        ];
    }
}