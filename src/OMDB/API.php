<?php


namespace App\OMDB;


use App\Client\OMDBClient;
use Symfony\Component\Cache\Adapter\AdapterInterface;

class API
{
    /**
     * @var OMDBClient
     */
    private $client;
    private $APIKey;
    /**
     * @var AdapterInterface
     */
    private $adapter;

    public function __construct(OMDBClient $client, AdapterInterface $adapter, $OMDBAPIKey)
    {
        $this->client = $client;
        $this->APIKey = $OMDBAPIKey;
        $this->adapter = $adapter;
    }

    public function getMovie($id)
    {
        $item = $this->adapter->getItem("movie_$id");

        if (!$item->isHit()) {
            $response = $this->client->get('http://www.omdbapi.com/', [
                'query' => [
                    'apikey' => $this->APIKey,
                    'i' => $id
                ]
            ]);

            $data = json_decode($response->getBody()->getContents(), true);
            $item->expiresAfter(\DateInterval::createFromDateString('1 hour'));
            $item->set($data);
            $this->adapter->save($item);
        }

        return $item->get();
    }

    public function getPosterURL($id)
    {
        return "http://img.omdbapi.com/?apikey=$this->APIKey&i=$id";
    }
}