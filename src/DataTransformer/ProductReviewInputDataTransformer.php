<?php

declare(strict_types=1);

namespace App\DataTransformer;

use ApiPlatform\Core\DataTransformer\DataTransformerInitializerInterface;
use ApiPlatform\Core\Serializer\AbstractItemNormalizer;
use App\DTO\ProductReviewInput;
use App\Entity\ProductReview;

final class ProductReviewInputDataTransformer implements DataTransformerInitializerInterface
{
    /**
     * {@inheritdoc}
     */
    public function transform($data, string $to, array $context = [])
    {
        $existingProductReview = $context[AbstractItemNormalizer::OBJECT_TO_POPULATE] ?? null;
        if ($existingProductReview instanceOf ProductReview) {
            // return $existingProductReview->updateFromDTO($data);
        }

        return ProductReview::forProductByUser(
            $data->product(),
            $data->user(),
            $data->review()
        );
    }

     /**
     * {@inheritdoc}
     */
    public function initialize(string $inputClass, array $context = [])
    {
        $existingProductReview = $context[AbstractItemNormalizer::OBJECT_TO_POPULATE] ?? null;
        if (!$existingProductReview) {
            return new ProductReviewInput();
        }

        $existingProductReview = new ProductReviewInput();

        return $existingProductReview;
    }

     /**
     * {@inheritdoc}
     */
    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        if ($data instanceof ProductReview) {
            return false;
        }

        return ProductReview::class === $to && null !== ($context['input']['class'] ?? null);
    }
}
