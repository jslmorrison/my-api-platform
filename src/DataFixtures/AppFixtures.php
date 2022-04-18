<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

final class AppFixtures extends Fixture
{
    private $faker;

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($i = 0; $i < 100; $i++) {
            $user = User::namedWithEmail($faker->name, $faker->safeEmail);
            $manager->persist($user);
        }

        $manager->flush();
    }
}
