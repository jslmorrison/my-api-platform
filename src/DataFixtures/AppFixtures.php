<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Product;
use App\Entity\ProductReview;
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
            $user = User::namedWithEmail($faker->name(), $faker->unique()->safeEmail());
            $this->addReference('user' . $i, $user);
            $manager->persist($user);

            $product = Product::named($faker->unique()->word());
            $this->addReference('product' . $i, $product);
            $manager->persist($product);

            if ($i % 2 === 0) {
                $productReview = ProductReview::forProductByUser(
                    $this->getReference('product' . rand(0, $i)),
                    $this->getReference('user' . rand(0, $i)),
                    $faker->realText()
                );
                $manager->persist($productReview);
            }
        }

        $manager->flush();
    }
}
