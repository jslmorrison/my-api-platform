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

    public function testCreateUserWithValidData()
    {
        $response = $this->client->request(
            'POST',
            '/api/users',
            [
                'json' => [
                    'name' => 'Test User',
                    'email' => 'test.user@example.com',
                ]
            ]
        );
        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertMatchesRegularExpression('~^/api/users/[0-9a-f]{8}-(?:[0-9a-f]{4}-){3}[0-9a-f]{12}~', $response->toArray()['@id']);
        $this->assertMatchesResourceItemJsonSchema(User::class);
    }

    public function testCreateUserWithInvalidData()
    {
        $response = $this->client->request(
            'POST',
            '/api/users',
            [
                'json' => [
                    'name' => '',
                    'email' => '',
                ]
            ]
        );
        $this->assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertJsonContains([
            '@type' => 'ConstraintViolationList',
            'hydra:title' => 'An error occurred',
            "violations" => [
                [
                    "propertyPath" => "name",
                    "message" => "This value should not be blank.",
                ],
                [
                    "propertyPath" => "email",
                    "message" => "This value should not be blank.",
                ]
            ]
        ]);
    }

    public function testCreateUserWithDuplicateEmail()
    {
        $this->createUser('Foo Barr', 'foo.barr@example.com');
        $response = $this->client->request(
            'POST',
            '/api/users',
            [
                'json' => [
                    'name' => 'Jimmy Wong',
                    'email' => 'foo.barr@example.com',
                ]
            ]
        );
        $this->assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertJsonContains([
            '@type' => 'ConstraintViolationList',
            'hydra:title' => 'An error occurred',
            "violations" => [
                [
                    "propertyPath" => "email",
                    "message" => "This value is already used.",
                ],
            ]
        ]);
    }

    public function testPatchUserWithValidData()
    {
        $user = $this->createUser('Test User', 'test.user@example.com');
        $this->client->request(
            'PATCH',
            '/api/users/' . $user->id(),
            [
                'headers' => [
                    'Content-Type' => 'application/merge-patch+json',
                ],
                'json' => [
                    'name' => 'UpdatedTest User',
                    'email' => 'updatedtest.user@example.com',
                ]
            ]
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertJsonContains([
            '@type' => 'User',
            '@id' => '/api/users/' . $user->id()->__toString(),
            'name' => 'UpdatedTest User',
            'email' => 'updatedtest.user@example.com',
            'createdAt' => $user->createdAt()->format('c'),
            'enabled' => $user->isEnabled(),
        ]);
    }

    public function testPatchUserWithMissingData()
    {
        $user = $this->createUser('Test User', 'test.user@example.com');
        $this->client->request(
            'PATCH',
            '/api/users/' . $user->id(),
            [
                'headers' => [
                    'Content-Type' => 'application/merge-patch+json',
                ],
                'json' => [
                    'name' => '',
                    'email' => '',
                ]
            ]
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);
        $this->assertJsonContains([
            '@type' => 'ConstraintViolationList',
            'hydra:title' => 'An error occurred',
            "violations" => [
                [
                    "propertyPath" => "name",
                    "message" => "This value should not be blank.",
                ],
                [
                    "propertyPath" => "email",
                    "message" => "This value should not be blank.",
                ]
            ]
        ]);
    }

    public function testPatchUserWithSameEmailAndDifferentName()
    {
        $user = $this->createUser('Test User', 'test.user@example.com');
        $this->client->request(
            'PATCH',
            '/api/users/' . $user->id(),
            [
                'headers' => [
                    'Content-Type' => 'application/merge-patch+json',
                ],
                'json' => [
                    'name' => 'UpdatedTest User',
                    'email' => 'test.user@example.com',
                ]
            ]
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertJsonContains([
            '@type' => 'User',
            '@id' => '/api/users/' . $user->id()->__toString(),
            'name' => 'UpdatedTest User',
            'email' => 'test.user@example.com',
            'createdAt' => $user->createdAt()->format('c'),
            'enabled' => $user->isEnabled(),
        ]);
    }

    public function testDeleteUserWithValidData()
    {
        $user = $this->createUser('Test User', 'test.user@example.com');
        $this->client->request(
            'DELETE',
            '/api/users/' . $user->id(),
            [
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
            ]
        );
        $this->assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);
    }

    public function testDeleteUserWithInvalidData()
    {
        $this->client->request(
            'DELETE',
            '/api/users/a-bogus-id',
            [
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
            ]
        );
        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }
}
