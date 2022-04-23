<?php

namespace App\Tests;

use App\Entity\Product;

trait ProductResourceTestTrait
{
    protected static $kernel;
    private $product;

    public function createProduct(string $name): Product
    {
        $productRepository = self::$kernel->getContainer()
            ->get('doctrine.orm.default_entity_manager')
            ->getRepository(Product::class);
        $product = Product::named($name);

        $productRepository->add($product);

        return $product;
    }
}
