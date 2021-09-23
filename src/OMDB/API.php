<?php


namespace App\OMDB;


use App\Client\OMDBClient;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class API
{
    private OMDBClient $client;
    private string $APIKey;
    private AdapterInterface $adapter;
    private DenormalizerInterface $denormalizer;

    public function __construct(OMDBClient $client, AdapterInterface $adapter, string $OMDBAPIKey, DenormalizerInterface $denormalizer)
    {
        $this->client = $client;
        $this->APIKey = $OMDBAPIKey;
        $this->adapter = $adapter;
        $this->denormalizer = $denormalizer;
    }

    public function getMovie($id): OmdbMovie
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
            $item->expiresAfter(\DateInterval::createFromDateString('1 week'));
            $item->set($data);
            $this->adapter->save($item);
        }

        $omdbMovie = $this->denormalizer->denormalize($item->get(), OmdbMovie::class);
        if ($omdbMovie instanceof OmdbMovie)
            return $omdbMovie;

        throw new \Exception('Error deserializing movie.');
    }

    public function getPosterURL($id): string
    {
        $movie = $this->getMovie($id);
        if ($movie->Poster)
            return $movie->Poster;

        return "http://img.omdbapi.com/?apikey=$this->APIKey&i=$id";
    }
}