<?php

namespace App\Factory;

use App\Entity\Movie;
use App\Repository\MovieRepository;
use Zenstruck\Foundry\RepositoryProxy;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;

/**
 * @extends ModelFactory<Movie>
 *
 * @method static Movie|Proxy createOne(array $attributes = [])
 * @method static Movie[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static Movie|Proxy find(object|array|mixed $criteria)
 * @method static Movie|Proxy findOrCreate(array $attributes)
 * @method static Movie|Proxy first(string $sortedField = 'id')
 * @method static Movie|Proxy last(string $sortedField = 'id')
 * @method static Movie|Proxy random(array $attributes = [])
 * @method static Movie|Proxy randomOrCreate(array $attributes = [])
 * @method static Movie[]|Proxy[] all()
 * @method static Movie[]|Proxy[] findBy(array $attributes)
 * @method static Movie[]|Proxy[] randomSet(int $number, array $attributes = [])
 * @method static Movie[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static MovieRepository|RepositoryProxy repository()
 * @method Movie|Proxy create(array|callable $attributes = [])
 */
final class MovieFactory extends ModelFactory
{
    private const IMDB_IDS = [
        'tt5073642',
        'tt0022913',
        'tt7914416',
        'tt8772262',
        'tt0047573',
        'tt0084787',
        'tt0477348',
        'tt0076786',
        'tt1034415',
        'tt0083624',
        'tt0095312',
        'tt8663516',
        'tt0099253',
        'tt0104070',
        'tt0094761',
        'tt0051418',
        'tt0216651',
        'tt7784604',
        'tt0087799',
        'tt0100814',
        'tt5932728',
        'tt0082696',
        'tt0080761',
        'tt0070047',
        'tt0093773',
        'tt0095016',
        'tt0829482',
        'tt0087015',
        'tt0107504',
        'tt5308322',
        'tt0113409',
        'tt0124014',
        'tt0077651',
        'tt0133093',
        'tt0074156',
        'tt0080684',
        'tt1343727',
        'tt0082971',
        'tt0076740',
        'tt0078788',
        'tt1780798',
        'tt1396484',
        'tt3235888',
        'tt0103919',
        'tt0100157',
        'tt5052448',
        'tt0010323',
        'tt0040068',
        'tt0144084',
        'tt0054215',
        'tt0090605',
        'tt0102926',
        'tt0063350',
        'tt0063522',
        'tt0053459',
        'tt0457430',
    ];

    public function __construct()
    {
        parent::__construct();
        // TODO inject services if required (https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services)
    }

    protected function getDefaults(): array
    {
        $startTime = self::faker()->dateTimeBetween('-20 years', '2 months');

        return [
            'imdb' => self::faker()->randomElement(self::IMDB_IDS),
            'yearFeasted' => (int)$startTime->format('Y'),
            'startTime' => $startTime,
            'lukeBit' => self::faker()->paragraph(),
        ];
    }

    protected function initialize(): self
    {
        // see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
        return $this// ->afterInstantiate(function(Movie $movie) {})
            ;
    }

    protected static function getClass(): string
    {
        return Movie::class;
    }
}
