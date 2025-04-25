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
    ) {
    }
    public function load(ObjectManager $manager): void
    {
        $users = $manager->getRepository(User::class)->findAll();
        unset($users[0]);
        $videoGames = $manager->getRepository(VideoGame::class)->findAll();

        foreach ($videoGames as $videoGame) {
            $total = 0;
            $nbReview = rand(1, 3);
            for ($i = 0; $i < $nbReview; $i++) {
                $rating = rand(1, 5);
                $total += $rating;
                $review = new Review();
                match ($rating) {
                    1 => $videoGame->getNumberOfRatingsPerValue()->increaseOne(),
                    2 => $videoGame->getNumberOfRatingsPerValue()->increaseTwo(),
                    3 => $videoGame->getNumberOfRatingsPerValue()->increaseThree(),
                    4 => $videoGame->getNumberOfRatingsPerValue()->increaseFour(),
                    5 => $videoGame->getNumberOfRatingsPerValue()->increaseFive(),
                };
                $review->setRating($rating);
                $review->setComment($this->faker->paragraphs(6, true));
                $review->setUser($users[array_rand($users)]);
                $review->setVideoGame($videoGame);
                $manager->persist($review);
            }
            $videoGame->setRating($total / $nbReview);
        }


        $manager->flush();
    }
    public function getDependencies(): array
    {
        return [UserFixtures::class, VideoGameFixtures::class];
    }
}
