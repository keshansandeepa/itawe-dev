<?php


namespace App\Tests\Unit;


use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class BookUnitTest extends WebTestCase
{


    public function testBookIndexReturnJsonResponse()
    {
        $client = static::createClient();
        $client->request('GET', '/api/books');
        $this->assertResponseIsSuccessful();
    }
}