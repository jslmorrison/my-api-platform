<?php

namespace App\Tests\Functional;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\ProductReview;
use App\Tests\ProductResourceTestTrait;
use App\Tests\ProductReviewResourceTestTrait;
use App\Tests\UserResourceTestTrait;
use Symfony\Component\HttpFoundation\Response;

class ProductReviewResourceTest extends ApiTestCase
{
    use ProductResourceTestTrait;
    use UserResourceTestTrait;
    use ProductReviewResourceTestTrait;

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
        $product1 = $this->createProduct('product1');
        $user1 = $this->createUser('Test User1', 'test.user1@example.com');
        $this->createProductReview($product1, $user1, 'Product review for product1 by user 1');

        $product2 = $this->createProduct('product2');
        $user2 = $this->createUser('Test User2', 'test.user2@example.com');
        $this->createProductReview($product2, $user2, 'Product review for product2 by user 2');

        $response = $this->client->request(
            'GET',
            '/api/product_reviews',
            [
                'headers' => [
                    'Content-Type' => 'application/json',
                ]
            ]
        );
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertMatchesResourceCollectionJsonSchema(ProductReview::class);
        $this->assertEquals(2, $response->toArray()['hydra:totalItems']);
    }

    public function testGetItem()
    {
        $user = $this->createUser('Test User', 'test.user@example.com');
        $product = $this->createProduct('Product 1');
        $productReview = $this->createProductReview($product, $user, 'Hello Review');
        $this->client->request(
            'GET',
            '/api/product_reviews/' . $productReview->id(),
            [
                'headers' => [
                    'Content-Type' => 'application/json',
                ]
            ]
        );
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertEquals(
            $this->client->getResponse()->toArray()['@id'],
            '/api/product_reviews/' . $productReview->id()->__toString(),
        );
    }

    public function testCreateProductReviewWithValidData()
    {
        $this->markTestIncomplete();
    }
}
