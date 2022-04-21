<?php

declare(strict_types=1);

namespace App\DataTransformer;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use App\DTO\UserOutput;
use App\Entity\User;

final class UserOutputDataTransformer implements DataTransformerInterface
{
    /**
     * {@inheritdoc}
     */
    public function transform($data, string $to, array $context = [])
    {
        $output = new UserOutput();
        $output->name = $data->name();
        $output->email = $data->email();
        $output->createdAt = $data->createdAt();
        $output->enabled = $data->isEnabled();

        return $output;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        return UserOutput::class === $to && $data instanceof User;
    }
}
