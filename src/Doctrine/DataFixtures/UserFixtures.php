<?php

namespace App\Doctrine\DataFixtures;

use App\Model\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use function array_fill_callback;

final class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 10; $i++) {
            $users = (new User)
                ->setEmail(sprintf('user+%d@email.com', $i))
                ->setPlainPassword('password')
                ->setUsername(sprintf('user+%d', $i));
            $manager->persist($users);
        }

        $manager->flush();
    }
}
