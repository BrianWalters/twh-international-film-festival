<?php

namespace App\Factory;

use App\Entity\Rating;
use App\Repository\RatingRepository;
use Zenstruck\Foundry\RepositoryProxy;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;

/**
 * @extends ModelFactory<Rating>
 *
 * @method static Rating|Proxy createOne(array $attributes = [])
 * @method static Rating[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static Rating|Proxy find(object|array|mixed $criteria)
 * @method static Rating|Proxy findOrCreate(array $attributes)
 * @method static Rating|Proxy first(string $sortedField = 'id')
 * @method static Rating|Proxy last(string $sortedField = 'id')
 * @method static Rating|Proxy random(array $attributes = [])
 * @method static Rating|Proxy randomOrCreate(array $attributes = [])
 * @method static Rating[]|Proxy[] all()
 * @method static Rating[]|Proxy[] findBy(array $attributes)
 * @method static Rating[]|Proxy[] randomSet(int $number, array $attributes = [])
 * @method static Rating[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static RatingRepository|RepositoryProxy repository()
 * @method Rating|Proxy create(array|callable $attributes = [])
 */
final class RatingFactory extends ModelFactory
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function getDefaults(): array
    {
        $date = self::faker()->dateTimeBetween('-10 years', '0 days');

        return [
            'movie' => MovieFactory::random(),
            'score' => self::faker()->randomFloat(1, .5, 5),
            'rater' => self::faker()->name(),
            'createdAt' => $date,
            'updatedAt' => $date,
        ];
    }

    protected function initialize(): self
    {
        // see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
        return $this
            // ->afterInstantiate(function(Rating $rating) {})
        ;
    }

    protected static function getClass(): string
    {
        return Rating::class;
    }
}
