<?php

namespace App\Tests\Unit;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BookUnitTest extends WebTestCase
{
    public function testBookIndexReturnJsonResponse()
    {
        $client = static::createClient();
        $client->request('GET', '/api/books');
        $this->assertResponseIsSuccessful();
    }
}
