<?php

declare(strict_types=1);

namespace App\DataTransformer;

use ApiPlatform\Core\DataTransformer\DataTransformerInitializerInterface;
use ApiPlatform\Core\Serializer\AbstractItemNormalizer;
use App\DTO\UserInput;
use App\Entity\User;

final class UserInputDataTransformer implements DataTransformerInitializerInterface
{
    /**
     * {@inheritdoc}
     */
    public function transform($data, string $to, array $context = [])
    {
        $existingUser = $context[AbstractItemNormalizer::OBJECT_TO_POPULATE] ?? null;
        if ($existingUser instanceOf User) {
            return $existingUser->updateFromDTO($data);
        }

        return User::namedWithEmail($data->name, $data->email);
    }

    /**
     * {@inheritdoc}
     */
    public function initialize(string $inputClass, array $context = [])
    {
        $existingUser = $context[AbstractItemNormalizer::OBJECT_TO_POPULATE] ?? null;
        if (!$existingUser) {
            return new UserInput();
        }

        $userInput = new UserInput();
        $userInput->name = $existingUser->name();
        $userInput->email = $existingUser->email();
        // todo may use UserInput::fromEntity($existingUser) instead?

        return $userInput;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        if ($data instanceof User) {
          return false;
        }

        return User::class === $to && null !== ($context['input']['class'] ?? null);
    }
}
