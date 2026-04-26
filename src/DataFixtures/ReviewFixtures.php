<?php

namespace App\DataFixtures;

use App\Entity\Review;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ReviewFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('hu_HU');

        $companies = ['Apple', 'Google', 'Tesla', 'Amazon', 'Microsoft'];

        for ($i = 0; $i < 15; $i++) {
            $review = new Review();
            $review->setCompanyName($companies[array_rand($companies)]);
            $review->setRating(random_int(1, 5));
            $review->setReviewText($faker->paragraph(3));
            $review->setAuthorEmail($faker->email());

            $manager->persist($review);
        }

        $manager->flush();
    }
}
