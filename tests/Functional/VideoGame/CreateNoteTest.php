<?php

namespace App\Tests\Functional\VideoGame;
use App\Model\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class CreateNoteTest extends WebTestCase
{
    public function testCreateNote(): void        
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get('doctrine')->getRepository(User::class);
        $testUser = $userRepository->findOneBy(['email' => 'user+0@email.com']);
        $client->loginUser($testUser);

        $client->request('GET', '/jeu-video-1');
        self::assertResponseIsSuccessful();

        $client->submitForm('Poster', [
            'review[rating]' => 5,
            'review[comment]' => 'Super jeu vidéo',
        ]);

        self::assertResponseStatusCodeSame(Response::HTTP_FOUND);

        $client->followRedirect();
    }
}