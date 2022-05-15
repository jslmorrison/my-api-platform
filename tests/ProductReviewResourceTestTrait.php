<?php

namespace App\Tests;

use App\Entity\Product;
use App\Entity\ProductReview;
use App\Entity\User;

trait ProductReviewResourceTestTrait
{
    protected static $kernel;

    public function createProductReview(Product $product, User $user, string $review): ProductReview
    {
        $productReview = ProductReview::forProductByUser($product, $user, $review);

        $productReviewRepository = self::$kernel->getContainer()
            ->get('doctrine.orm.default_entity_manager')
            ->getRepository(ProductReview::class);
        $productReviewRepository->add($productReview);

        return $productReview;
    }
}
