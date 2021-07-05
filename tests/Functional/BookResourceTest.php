<?php


namespace App\Tests\Functional;


use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\Book;
use Faker\Factory;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;

class BookResourceTest extends ApiTestCase
{

//    use RefreshDatabaseTrait;
    public function testRetrievesBookCollection():void
    {

        $client = self::createClient();
        $client->request('GET', '/api/books');
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertResponseStatusCodeSame(200);
        $this->assertJsonContains([
            '@context' => '/api/contexts/Book',
            '@id' => '/api/books',
            '@type' => 'hydra:Collection',
            'hydra:totalItems' => 12,
            'hydra:view' => [
                '@id' => '/api/books?page=1',
                '@type' => 'hydra:PartialCollectionView',
                'hydra:first' => '/api/books?page=1',
                'hydra:last' => '/api/books?page=2',
                'hydra:next' => '/api/books?page=2'

            ],
        ]);

        $this->assertMatchesResourceCollectionJsonSchema(Book::class);
    }
}