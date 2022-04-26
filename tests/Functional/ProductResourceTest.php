<?php

namespace App\Tests\Functional;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\Product;
use App\Tests\ProductResourceTestTrait;
use Symfony\Component\HttpFoundation\Response;

class ProductResourceTest extends ApiTestCase
{
    use ProductResourceTestTrait;

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
        $this->createProduct('Product 1');
        $this->createProduct('Product 2');
        $response = $this->client->request(
            'GET',
            '/api/products',
            [
                'headers' => [
                    'Content-Type' => 'application/json',
                ]
            ]
        );
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertMatchesResourceCollectionJsonSchema(Product::class);
        $this->assertEquals(2, $response->toArray()['hydra:totalItems']);
    }

    public function testGetItem()
    {
        $product = $this->createProduct('Product 1');
        $this->client->request(
            'GET',
            '/api/products/' . $product->id(),
            [
                'headers' => [
                    'Content-Type' => 'application/json',
                ]
            ]
        );
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertEquals(
            $this->client->getResponse()->toArray()['@id'],
            '/api/products/' . $product->id()->__toString(),
        );
    }

    public function testCreateProductWithValidData()
    {
        $response = $this->client->request(
            'POST',
            '/api/products',
            [
                'json' => [
                    'name' => 'Test Product',
                ]
            ]
        );
        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertMatchesRegularExpression('~^/api/products/[0-9a-f]{8}-(?:[0-9a-f]{4}-){3}[0-9a-f]{12}~', $response->toArray()['@id']);
        $this->assertMatchesResourceItemJsonSchema(Product::class);
    }

    public function testCreateProductWithMissingData()
    {
        $this->client->request(
            'POST',
            '/api/products',
            [
                'json' => [
                    'name' => '',
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
            ]
        ]);
    }

    public function testCreateProductWithDuplicateData()
    {
        $this->createProduct('Test Product1');
        $this->client->request(
            'POST',
            '/api/products',
            [
                'json' => [
                    'name' => 'Test Product1',                ]
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
                    "message" => "This value is already used.",
                ],
            ]
        ]);
    }

    public function testPatchProductWithValidaData()
    {
        $product = $this->createProduct('Test Product1');
        $this->client->request(
            'PATCH',
            '/api/products/' . $product->id(),
            [
                'headers' => [
                    'Content-Type' => 'application/merge-patch+json',
                ],
                'json' => [
                    'name' => 'Patched Test product 1',
                ]
            ]
        );
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertJsonContains([
            '@type' => 'Product',
            '@id' => '/api/products/' . $product->id()->__toString(),
            'name' => 'Patched Test product 1',
        ]);
    }

    public function testPatchProductWithMissingData()
    {
        $product = $this->createProduct('Test Product1');
        $this->client->request(
            'PATCH',
            '/api/products/' . $product->id(),
            [
                'headers' => [
                    'Content-Type' => 'application/merge-patch+json',
                ],
                'json' => [
                    'name' => '',
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
            ]
        ]);
    }

    public function testPatchProductWithDuplicateName()
    {
        $product = $this->createProduct('Test Product 1');
        $this->client->request(
            'PATCH',
            '/api/products/' . $product->id(),
            [
                'headers' => [
                    'Content-Type' => 'application/merge-patch+json',
                ],
                'json' => [
                    'name' => 'Test Product 1',
                ]
            ]
        );
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertJsonContains([
            '@type' => 'Product',
            '@id' => '/api/products/' . $product->id()->__toString(),
            'name' => 'Test Product 1',
        ]);
    }

    public function testDeleteProductWithValidData()
    {
        $product = $this->createProduct('Test Product1');
        $this->client->request(
            'DELETE',
            '/api/products/' . $product->id(),
            [
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
            ]
        );
        $this->assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);
    }

    public function testDeleteProductWithInValidData()
    {
        $this->client->request(
            'DELETE',
            '/api/products/a-bogus-id',
            [
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
            ]
        );
        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }
}
