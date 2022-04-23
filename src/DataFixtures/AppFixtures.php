<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Product;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

final class AppFixtures extends Fixture
{
    private $faker;

    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create();

        for ($i = 0; $i < 100; $i++) {
            $user = User::namedWithEmail($faker->name(), $faker->safeEmail());
            $manager->persist($user);

            $product = Product::named($faker->word());
            $manager->persist($product);
        }

        $manager->flush();
    }
}
