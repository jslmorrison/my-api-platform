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
}
