<?php

namespace App\Factory;

use App\Entity\Book;
use App\Repository\BookRepository;
use Symfony\Component\String\Slugger\SluggerInterface;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @method static     Book|Proxy createOne(array $attributes = [])
 * @method static     Book[]|Proxy[] createMany(int $number, $attributes = [])
 * @method static     Book|Proxy find($criteria)
 * @method static     Book|Proxy findOrCreate(array $attributes)
 * @method static     Book|Proxy first(string $sortedField = 'id')
 * @method static     Book|Proxy last(string $sortedField = 'id')
 * @method static     Book|Proxy random(array $attributes = [])
 * @method static     Book|Proxy randomOrCreate(array $attributes = [])
 * @method static     Book[]|Proxy[] all()
 * @method static     Book[]|Proxy[] findBy(array $attributes)
 * @method static     Book[]|Proxy[] randomSet(int $number, array $attributes = [])
 * @method static     Book[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static     BookRepository|RepositoryProxy repository()
 * @method Book|Proxy create($attributes = [])
 */
final class BookFactory extends ModelFactory
{
    private SluggerInterface $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        parent::__construct();

        $this->slugger = $slugger;
    }

    protected function getDefaults(): array
    {
        return [
            'title' => self::faker()->sentences(self::faker()->numberBetween(1, 2), true),
            'description' => self::faker()->paragraphs(1, true),
            'isbn' => self::faker()->isbn10(),
            'publication_date' => self::faker()->dateTimeThisDecade(),
            'price' => self::faker()->numberBetween(900, 1500),
            'created_at' => self::faker()->dateTimeThisDecade(),
        ];
    }

    protected function initialize(): self
    {
        // see https://github.com/zenstruck/foundry#initialization
        return $this
             ->afterInstantiate(function (Book $book) {
                 $book->setSlug($this->slugger->slug($book->getTitle()));
             })
        ;
    }

    protected static function getClass(): string
    {
        return Book::class;
    }
}
