<?php

namespace App\Doctrine\DataFixtures;

use App\Model\Entity\Review;
use App\Model\Entity\User;
use App\Model\Entity\VideoGame;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Generator;
use function array_fill_callback;

final class ReviewFixtures extends Fixture implements DependentFixtureInterface
{

    public function __construct(
        private readonly Generator $faker,
    ){}
    public function load(ObjectManager $manager): void
    {

        $users = $manager->getRepository(User::class)->findAll();
        $videoGames = $manager->getRepository(VideoGame::class)->findAll();

        $reviews = array_fill_callback(
            0,
            300,
            fn(int $index): Review => (new Review)
                ->setRating(($index % 5) + 1)
                ->setComment($this->faker->paragraphs(6, true))
                ->setUser($users[array_rand($users)])
                ->setVideoGame($videoGames[array_rand($videoGames)])
        );

        array_walk($reviews, [$manager, 'persist']);

        $manager->flush();
    }
    public function getDependencies(): array
    {
        return [UserFixtures::class, VideoGameFixtures::class];
    }
}
