<?php

declare(strict_types=1);

namespace App\Tests\Functional\VideoGame;

use App\Model\Entity\Tag;
use App\Tests\Functional\FunctionalTestCase;

final class FilterTest extends FunctionalTestCase
{
    /**
     * Summary of tagsProvider
     * @return array<int[]>
     */
    public static function tagsProvider(): iterable
    {
        return [
            [],
            [1],
            [1, 2],
        ];
    }
    public function testShouldListTenVideoGames(): void
    {
        $this->get('/');
        self::assertResponseIsSuccessful();
        self::assertSelectorCount(10, 'article.game-card');
        $this->client->clickLink('2');
        self::assertResponseIsSuccessful();
    }

    public function testShouldFilterVideoGamesBySearch(): void
    {
        $this->get('/');
        self::assertResponseIsSuccessful();
        self::assertSelectorCount(10, 'article.game-card');
        $this->client->submitForm('Filtrer', ['filter[search]' => 'Jeu vidéo 49'], 'GET');
        self::assertResponseIsSuccessful();
        self::assertSelectorCount(1, 'article.game-card');
    }

    public function testTags(): void
    {
        foreach (self::tagsProvider() as $tags) {
            $this->get('/');
            $form = $this->client->getCrawler()->selectButton('Filtrer')->form();
            $form['filter[tags]'] = $tags;
            $this->client->submit($form);
            self::assertResponseIsSuccessful();
            self::assertSelectorCount(10, 'article.game-card');
        }
    }
    public function testFalseTags(): void
    {
        //vérifier si le tag existe dans la base de données
        $tagRepository = self::getContainer()->get('doctrine')->getRepository(Tag::class);
        $tag = $tagRepository->findOneBy(['id' => 999]);
        self::assertNull($tag);
    }
}
