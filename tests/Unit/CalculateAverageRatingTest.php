<?php

namespace App\Tests\Unit;

use App\Model\Entity\Review;
use App\Model\Entity\VideoGame;
use App\Rating\RatingHandler;
use PHPUnit\Framework\TestCase;

class CalculateAverageRatingTest extends TestCase
{

    //Créer un test qui vérifie que la méthode calculateAverage retourne la moyenne des notes
    /**
     * @dataProvider videoGameProvider
     */
    public function testCalculateAverageRating(VideoGame $videoGame, ?int $expectedAverage): void
    {
        $ratingHandler = new RatingHandler();
        $ratingHandler->calculateAverage($videoGame);

        self::assertSame($expectedAverage, $videoGame->getAverageRating());
    }

    //Créer une méthode qui retourne un tableau de jeux vidéos avec des notes
    /**
     * @return iterable<array{VideoGame, ?int}>
     */
    public function videoGameProvider(): iterable
    {
        return [
            [new VideoGame(), null],
            [self::newVideoGame(3, 4, 5), 4],
            [self::newVideoGame(1, 2, 3, 4, 5), 3],
            [self::newVideoGame(1, 1, 1, 1, 1), 1],
            [self::newVideoGame(5, 5, 5, 5, 5), 5],
            [self::newVideoGame(1, 2, 3, 4, 5, 1, 2, 3, 4, 5), 3],
        ];
    }

    //Créer une méthode qui retourne un objet VideoGame avec des reviews
    private function newVideoGame(int ...$ratings): VideoGame
    {
        $videoGame = new VideoGame();

        foreach ($ratings as $rating) {
            $videoGame->getReviews()->add((new Review())->setRating($rating));
        }

        return $videoGame;
    }
}
