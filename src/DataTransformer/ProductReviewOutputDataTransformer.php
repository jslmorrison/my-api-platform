<?php

declare(strict_types=1);

namespace App\DataTransformer;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use App\DTO\ProductReviewOutput;
use App\Entity\ProductReview;

final class ProductReviewOutputDataTransformer implements DataTransformerInterface
{
    /**
     * {@inheritdoc}
     */
    public function transform($data, string $to, array $context = [])
    {
        $output = new ProductReviewOutput();
        $output->user = $data->user();
        $output->product = $data->product();
        $output->review = $data->review();
        $output->createdAt = $data->createdAt();

        return $output;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        return ProductReviewOutput::class === $to && $data instanceof ProductReview;
    }
}
