<?php

namespace App\Tests\Functional;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\User;

use App\Tests\UserResourceTestTrait;
use Symfony\Component\HttpFoundation\Response;

class UserResourceTest extends ApiTestCase
{
    use UserResourceTestTrait;

    public function setUp(): void
    {
        $this->client = static::createClient();
    }

    public function tearDown(): void
    {
        parent::tearDown();
    }

    public function testGetCollection()
    {
        $this->createUser('Test User', 'test.user+1@example.com');
        $this->createUser('AnotherTest User', 'test.user+2@example.com');
        $response = $this->client->request(
            'GET',
            '/api/users',
            [
                'headers' => [
                    'Content-Type' => 'application/json',
                ]
            ]
        );
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertMatchesResourceCollectionJsonSchema(User::class);
        $this->assertEquals(2, $response->toArray()['hydra:totalItems']);
    }

    public function testGetItem()
    {
        $user = $this->createUser('Test User', 'test.user+1@example.com');
        $this->client->request(
            'GET',
            '/api/users/' . $user->id(),
            [
                'headers' => [
                    'Content-Type' => 'application/json',
                ]
            ]
        );
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertEquals(
            $this->client->getResponse()->toArray()['@id'],
            '/api/users/' . $user->id()->__toString(),
        );
    }
}
