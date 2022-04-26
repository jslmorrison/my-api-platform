<?php

declare(strict_types=1);

namespace App\DataTransformer;

use ApiPlatform\Core\DataTransformer\DataTransformerInitializerInterface;
use ApiPlatform\Core\Serializer\AbstractItemNormalizer;
use App\DTO\ProductInput;
use App\Entity\Product;

final class ProductInputDataTransformer implements DataTransformerInitializerInterface
{
    /**
     * {@inheritdoc}
     */
    public function transform($data, string $to, array $context = [])
    {
        $existingProduct = $context[AbstractItemNormalizer::OBJECT_TO_POPULATE] ?? null;
        if ($existingProduct instanceof Product) {
            return $existingProduct->updateFromDTO($data);
        }

        return Product::named($data->name);
    }

    /**
     * {@inheritdoc}
     */
    public function initialize(string $inputClass, array $context = [])
    {
        $existingProduct = $context[AbstractItemNormalizer::OBJECT_TO_POPULATE] ?? null;
        if (!$existingProduct) {
            return new ProductInput();
        }

        $productInput = new ProductInput();
        $productInput->name = $existingProduct->name();
        // todo may use ProductInput::fromEntity($existingProduct) instead?

        return $productInput;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        if ($data instanceof Product) {
          return false;
        }

        return Product::class === $to && null !== ($context['input']['class'] ?? null);
    }
}
