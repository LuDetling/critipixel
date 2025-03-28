<?php

namespace App\Tests\Unit;

use App\Model\Entity\NumberOfRatingPerValue;
use App\Model\Entity\Review;
use App\Model\Entity\VideoGame;
use App\Rating\RatingHandler;
use PHPUnit\Framework\TestCase;

class CountRatingPerValueTest extends TestCase
{
    /**
     * @dataProvider noteProvider
     */
    public function testRatingPerValue(VideoGame $videoGame, NumberOfRatingPerValue $numberOfRatingPerValue): void
    {
        $ratingHandler = new RatingHandler();
        $ratingHandler->countRatingsPerValue($videoGame);

        self::assertEquals($numberOfRatingPerValue, $videoGame->getNumberOfRatingsPerValue());
    }

    /**
     * @return iterable<array{VideoGame, ?int}>
     */
    public function noteProvider(): iterable
    {
        return [
            [
                new VideoGame(),
                null
            ],
            [
                self::newVideoGame(1, 2, 2, 3, 3, 3, 4, 4, 4, 4, 5, 5),
                self::noteByUsers(1, 2, 3, 4, 2)
            ],
            [
                self::newVideoGame(1, 1, 1, 1, 1, 1, 1, 1, 1, 1),
                self::noteByUsers(one: 10)
            ],
            [
                self::newVideoGame(1, 1, 1, 1, 2, 3, 4, 4, 5, 5, 5, 5),
                self::noteByUsers(one: 4, two: 1, three: 1, four: 2, five: 4)
            ]

        ];
    }

    //Créer une méthode qui retourne un objet VideoGame avec des notes
    private function newVideoGame(int ...$ratings): VideoGame
    {
        $videoGame = new VideoGame();
        foreach ($ratings as $rating) {
            $videoGame->getReviews()->add((new Review())->setRating($rating));
        }
        return $videoGame;
    }

    //Créer une méthode qui retourne un objet NumberOfRatingPerValue avec des notes
    private function noteByUsers(int $one = 0, int $two = 0, int $three = 0, int $four = 0, int $five = 0): NumberOfRatingPerValue
    {
        $numberOfRatingsPerValue = new NumberOfRatingPerValue();

        for ($i = 0; $i < $one; $i++) {
            $numberOfRatingsPerValue->increaseOne();
        }
        for ($i = 0; $i < $two; $i++) {
            $numberOfRatingsPerValue->increaseTwo();
        }
        for ($i = 0; $i < $three; $i++) {
            $numberOfRatingsPerValue->increaseThree();
        }
        for ($i = 0; $i < $four; $i++) {
            $numberOfRatingsPerValue->increaseFour();
        }
        for ($i = 0; $i < $five; $i++) {
            $numberOfRatingsPerValue->increaseFive();
        }

        return $numberOfRatingsPerValue;
    }
}
